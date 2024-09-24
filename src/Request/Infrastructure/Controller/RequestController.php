<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Controller;


use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Document\Application\Upload\UploadDocumentRequest;
use TFM\HolidaysManagement\Request\Application\Create\CreateRequestRequest;
use TFM\HolidaysManagement\Request\Application\Find\FindRequestRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetEventsCalendarRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestByUserRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestsRequest;
use TFM\HolidaysManagement\Request\Application\Update\UpdateRequestRequest;
use TFM\HolidaysManagement\Request\Application\Update\UpdateStatusRequestRequest;
use TFM\HolidaysManagement\Request\Domain\Exception\DaysCoincideWithOtherRequestsException;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestIdNotExistsException;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestNotAvailableDaysException;
use TFM\HolidaysManagement\Request\Infrastructure\Form\CreateRequestType;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetAllTypesRequestsRequest;
use function Lambdish\Phunctional\map;

final class RequestController extends AbstractController
{
    private CommandBus $commandBus;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private array $filters;

    public function __construct(
        CommandBus $commandBus,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(): Response
    {
        $this->init();

        $requests = $this->commandBus->handle(new GetRequestsRequest($this->filters));
        return $this->render('Request/index.html.twig', ['requests' => $requests]);
    }

    public function addRequest(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->requestForm(null, 'save_request');
        return $this->renderRequest($form, null);
    }

    public function saveNewRequest(Request $request): RedirectResponse
    {
        if (empty($this->getUser())) {
            return new RedirectResponse($this->urlGenerator->generate('ui_login'));
        }

        $form = $this->requestForm(null, 'save_request');
        $form->handleRequest($request);

        $filesToUpload = [];

        if ($request->files->get('file') > 0) {
            $filesToUpload = $request->files->get('file');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->commandBus->handle(
                    new CreateRequestRequest(
                        $form->getData()['description'],
                        $this->getUser()->id()->value(),
                        $form->getData()['typesRequest']->id()->value(),
                        StatusRequest::PENDING,
                        $form->getData()['requestPeriodStart'],
                        $form->getData()['requestPeriodEnd'],
                        ...map($this->requestFileToDocumentUploadRequest(), $filesToUpload)
                    )
                );
                $this->get('session')->getFlashBag()->add('ok',
                    $this->translator->trans('messages.save.ok'));
                return new RedirectResponse($this->urlGenerator->generate('list_request'));

            } catch (RequestNotAvailableDaysException $exception) {
                $this->get('session')->getFlashBag()->add('warning', $exception->getMessage());
                return new RedirectResponse($this->urlGenerator->generate('add_request'));

            } catch (DaysCoincideWithOtherRequestsException $exception) {
                $this->get('session')->getFlashBag()->add('warning', $exception->getMessage());
                return new RedirectResponse($this->urlGenerator->generate('add_request'));

            } catch (Exception $exception) {
                throw new InvalidArgumentException(
                    sprintf("%s", $this->translator->trans($exception->getMessage()))
                );
            }
        }
        return new RedirectResponse($this->urlGenerator->generate('list_request'));
    }

    private function renderRequest($form, $request): Response
    {
        $user_id = $this->getUser()->id()->value();

        $listRequest = $this->commandBus->handle(new GetRequestByUserRequest($user_id));

        $events = $this->commandBus->handle(new GetEventsCalendarRequest($listRequest));
        $typesRequest = $this->commandBus->handle(new GetAllTypesRequestsRequest());


        return $this->render('Request/request.html.twig', [
            'requestForm' => $form->createView(),
            'listRequest' => $listRequest,
            'request' => $request,
            'events' => $events,
            'type_requests' => $typesRequest,
        ]);
    }

    private function requestForm($request, string $route): FormInterface
    {
        return $this->createForm(CreateRequestType::class,
            $request,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]);
    }

    public function editRequest(string $requestId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $request = $this->commandBus->handle(
                new FindRequestRequest($requestId)
            );

        } catch (Exception $exception) {
            throw new RequestIdNotExistsException($requestId);
        }
        $form = $this->requestForm($request, 'update_request');

        return $this->renderRequest($form, $request);
    }

    public function updateRequest(Request $request): RedirectResponse
    {
        if (empty($this->getUser())) {
            return new RedirectResponse($this->urlGenerator->generate('ui_login'));
        }

        $form = $this->requestForm(null, 'update_request');
        $form->handleRequest($request);

        $filesToUpload = [];

        if ($request->files->get('file') > 0) {
            $filesToUpload = $request->files->get('file');
        }

        try {

            if ($form->get('annulled')->isClicked()) {

                $this->commandBus->handle(new UpdateStatusRequestRequest(
                    $form->getData()['id'],
                    StatusRequest::ANNULLED
                ));

            } else {

                $this->commandBus->handle(new UpdateRequestRequest(
                    $form->getData()['id'],
                    $form->getData()['description'],
                    $this->getUser()->id()->value(),
                    $form->getData()['typesRequest']->id()->value(),
                    statusRequest::PENDING,
                    $form->getData()['requestPeriodStart'],
                    $form->getData()['requestPeriodEnd'],
                    ...map($this->requestFileToDocumentUploadRequest(), $filesToUpload)
                ));

            }
        } catch (Exception $exception) {
            $this->get('session')->getFlashBag()->add('error',
                $this->translator->trans('request.data.not.complete'));

            throw new InvalidArgumentException(sprintf($this->translator->trans('request.controller.update.form.error'),
                $exception->getMessage()));
        }

        $this->get('session')->getFlashBag()->add('ok',
            $this->translator->trans('messages.save.ok'));

        return new RedirectResponse($this->urlGenerator->generate('list_request'));
    }

    public function saveRequestCalendar(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $filesToUpload = [];
        if ($request->files->get('file') > 0) {
            $filesToUpload = $request->files->get('file');
        }
        try {
            $this->commandBus->handle(
                new CreateRequestRequest(
                    $request->get('description'),
                    $this->getUser()->id()->value(),
                    $request->get('type_request'),
                    StatusRequest::PENDING,
                    new DateTimeImmutable($request->get('start_date')),
                    new DateTimeImmutable($request->get('end_date')),
                    ...map($this->requestFileToDocumentUploadRequest(), $filesToUpload)
                )
            );

        } catch (RequestNotAvailableDaysException $exception) {
            return new Response('KO', Response::HTTP_NOT_ACCEPTABLE, ['content-type' => 'text/html']);
        } catch (DaysCoincideWithOtherRequestsException $exception) {
            return new Response('KO', Response::HTTP_NOT_ACCEPTABLE, ['content-type' => 'text/html']);
        } catch (Exception $exception) {
            throw new InvalidArgumentException(
                sprintf("%s", $this->translator->trans($exception->getMessage()))
            );
        }


        return new Response('OK', Response::HTTP_OK, ['content-type' => 'text/html']);
    }

    private function requestFileToDocumentUploadRequest(): callable
    {
        return static function (UploadedFile $uploadedFile) {
            return new UploadDocumentRequest(
                $uploadedFile->getPathname(),
                $uploadedFile->guessExtension(),
                pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)
            );
        };
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        $this->filters += ['user' => $user->id()->value()];

    }
}
