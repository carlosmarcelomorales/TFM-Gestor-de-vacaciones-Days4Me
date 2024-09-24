<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Update;

use DateTimeImmutable;
use TFM\HolidaysManagement\Request\Application\Calculate\CalculateAvailableDays;
use TFM\HolidaysManagement\Request\Application\Calculate\CalculateAvailableDaysRequest;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestIdNotExistsException;
use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Request\Domain\Model\Event\UpdateStatusRequestDomainEvent;
use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequest;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequestRequest;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDays;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDaysRequest;

final class UpdateStatusRequest
{
    private RequestRepository $repository;
    private DomainEventBus $eventBus;
    private GetStatusRequest $getStatusRequestService;
    private CalculateAvailableDays $calculateAvailableDays;
    private UpdateUserAvailableDays $updateUserAvailableDays;

    public function __construct(
        RequestRepository $repository,
        DomainEventBus $eventBus,
        GetStatusRequest $getStatusRequestService,
        CalculateAvailableDays $calculateAvailableDays,
        UpdateUserAvailableDays $updateUserAvailableDays

    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
        $this->getStatusRequestService = $getStatusRequestService;
        $this->calculateAvailableDays = $calculateAvailableDays;
        $this->updateUserAvailableDays = $updateUserAvailableDays;
    }

    public function __invoke(UpdateStatusRequestRequest $updateStatusRequest): Request
    {
        $request = $this->repository->getRequestById(new IdentUuid($updateStatusRequest->id()));

        if ($request === null) {
            throw new RequestIdNotExistsException($updateStatusRequest->id());
        }

        $statusRequest = ($this->getStatusRequestService)(new GetStatusRequestRequest($updateStatusRequest->statusRequest()));
        $user = $request->users();

        if ($request->typesRequest()->discountDays()) {

            if (statusRequest::DECLINED == $statusRequest->id() || statusRequest::ANNULLED == $statusRequest->id()) {

                $daysToReturnResponse = ($this->calculateAvailableDays)(new CalculateAvailableDaysRequest(
                    $user->id()->value(),
                    $request->requestPeriodStart(),
                    $request->requestPeriodEnd()
                ));

                ($this->updateUserAvailableDays)(new UpdateUserAvailableDaysRequest(
                    $user->id()->value(),
                    $user->emailAddress(),
                    ($user->availableDays() + $daysToReturnResponse->totalDaysToConsume()),
                    $user->accumulatedDays(),
                    $request->statusRequest()->name()
                ));
            }
        }

        $request->updateStatus(
            $statusRequest
        );

        $this->repository->save($request);

        $this->eventBus->publish(
            new UpdateStatusRequestDomainEvent(
                $request->id()->value(),
                $request->users()->id()->value(),
                $statusRequest->id()->value(),
                new DateTimeImmutable()
            )
        );

        return $request;
    }
}
