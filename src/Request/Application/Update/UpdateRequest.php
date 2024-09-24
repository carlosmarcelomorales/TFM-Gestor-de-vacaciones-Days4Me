<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Update;


use DateTimeImmutable;
use TFM\HolidaysManagement\Document\Application\Save\SaveDocument;
use TFM\HolidaysManagement\Document\Application\Save\SaveDocumentRequest;
use TFM\HolidaysManagement\Document\Application\Upload\UploadDocument;
use TFM\HolidaysManagement\Document\Application\Upload\UploadDocumentRequest;
use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;
use TFM\HolidaysManagement\Document\Domain\Model\ValueObject\newDocument;
use TFM\HolidaysManagement\Request\Application\Calculate\CalculateAvailableDays;
use TFM\HolidaysManagement\Request\Application\Calculate\CalculateAvailableDaysRequest;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestIdNotExistsException;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestNotAvailableDaysException;
use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Request\Domain\Model\Event\CreateRequestDomainEvent;
use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetTypeRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetTypeRequestRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUser;
use TFM\HolidaysManagement\User\Application\Search\GetUserRequest;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDays;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDaysRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

final class UpdateRequest
{
    private RequestRepository $repository;
    private DomainEventBus $eventBus;
    private CalculateAvailableDays $calculateAvailableDays;
    private GetUser $getUserService;
    private GetTypeRequest $getTypeRequestService;
    private GetStatusRequest $getStatusRequestService;
    private UploadDocument $uploadDocumentService;
    private SaveDocument $saveDocumentService;
    private UpdateUserAvailableDays $updateUserAvailableDays;

    public function __construct(
        RequestRepository $repository,
        DomainEventBus $eventBus,
        CalculateAvailableDays $calculateAvailableDays,
        GetUser $getUserService,
        GetTypeRequest $getTypeRequestService,
        GetStatusRequest $getStatusRequestService,
        UploadDocument $uploadDocumentService,
        SaveDocument $saveDocumentService,
        UpdateUserAvailableDays $updateUserAvailableDays
    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
        $this->calculateAvailableDays = $calculateAvailableDays;
        $this->getUserService = $getUserService;
        $this->getTypeRequestService = $getTypeRequestService;
        $this->getStatusRequestService = $getStatusRequestService;
        $this->uploadDocumentService = $uploadDocumentService;
        $this->saveDocumentService = $saveDocumentService;
        $this->updateUserAvailableDays = $updateUserAvailableDays;
    }

    public function __invoke(UpdateRequestRequest $requestRequest): Request
    {
        $request = $this->repository->getRequestById(new IdentUuid($requestRequest->id()));

        if ($request === null) {
            throw new RequestIdNotExistsException($requestRequest->id());
        }

        $user = ($this->getUserService)(new GetUserRequest($request->users()->id()->value()));
        $typeRequest = ($this->getTypeRequestService)(new GetTypeRequestRequest($requestRequest->typesRequest()));

        if (true === $typeRequest->discountDays() && 0 === $user->availableDays()) {
            throw new RequestNotAvailableDaysException();
        }

        $requestIsCreated = true;
        $accumulatedDays = $user->accumulatedDays();
        $availableDays = $user->availableDays();

        if ($typeRequest->discountDays()) {

            $daysToReturnResponse = ($this->calculateAvailableDays)(new CalculateAvailableDaysRequest(
                $user->id()->value(),
                $request->requestPeriodStart(),
                $request->requestPeriodEnd()
            ));

            ($this->updateUserAvailableDays)(new UpdateUserAvailableDaysRequest(
                $user->id()->value(),
                $user->emailAddress(),
                $user->availableDays() + $daysToReturnResponse->totalDaysToConsume(),
                $user->accumulatedDays(),
                $request->statusRequest()->name()
            ));

            $availableDaysResponse = ($this->calculateAvailableDays)(new CalculateAvailableDaysRequest(
                $user->id()->value(),
                $requestRequest->requestPeriodStart(),
                $requestRequest->requestPeriodEnd()
            ));

            $requestIsCreated = $availableDaysResponse->permitRequest();
            $accumulatedDays = $availableDaysResponse->totalDaysAccumulated();
            $availableDays = $availableDaysResponse->totalDaysAvailable();

        }

        if ($requestIsCreated) {
            $request->update(
                $requestRequest->description(),
                $user,
                $typeRequest,
                $requestRequest->requestPeriodStart(),
                $requestRequest->requestPeriodEnd(),
                new DateTimeImmutable()
            );

            $this->processDocument($requestRequest->toPrimitives(), $request);
            $this->repository->save($request);
            $this->processEvent($user, $accumulatedDays, $availableDays, $request->statusRequest()->name());

            return $request;
        }
    }

    protected function processDocument(array $documents, Request $request)
    {
        foreach ($documents as $documentToUpload) {
            $newDocument = new newDocument(
                $documentToUpload['imagePathUploaded'],
                $documentToUpload['imageExtension'],
                $documentToUpload['nameUploadedImage']
            );

            $this->uploadDocuments($newDocument);

            $doc = $this->saveDocuments($newDocument);
            $request->addDocuments($doc);
        }
    }

    protected function uploadDocuments(newDocument $newDocument): void
    {
        $documentUpload = new UploadDocumentRequest(
            $newDocument->imagePathUploaded(),
            $newDocument->imageExtension(),
            $newDocument->imagePathNew()
        );

        ($this->uploadDocumentService)($documentUpload);
    }

    protected function saveDocuments(newDocument $newDocument): Document
    {
        $documentSave = new SaveDocumentRequest(
            $newDocument->newImageName(),
            $newDocument->imagePathNew(),
        );

        return ($this->saveDocumentService)($documentSave);
    }

    protected function processEvent(User $user, int $accumulatedDays, int $availableDays, string $status): void
    {
        $this->eventBus->publish(
            new CreateRequestDomainEvent(
                $user->id()->value(),
                $user->emailAddress(),
                $availableDays,
                $accumulatedDays,
                $status,
                new DateTimeImmutable()
            )
        );
    }
}
