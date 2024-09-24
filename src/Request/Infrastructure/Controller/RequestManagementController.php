<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Controller;

use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Request\Application\Find\FindRequestRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetEventsCalendarRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestsRequest;
use TFM\HolidaysManagement\Request\Application\Update\UpdateStatusRequestRequest;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestIdNotExistsException;
use TFM\HolidaysManagement\Request\Infrastructure\Form\CreateRequestType;

final class RequestManagementController extends AbstractController
{
    private CommandBus $commandBus;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private array $filters;

    public function __construct(
        CommandBus $commandBus,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(): Response
    {
        $this->init();

        $requests = $this->commandBus->handle(new GetRequestsRequest($this->filters));
        return $this->render('Request/indexManagement.html.twig', ['requests' => $requests]);
    }

    private function renderRequest($form, $request): Response
    {

        $listRequest = $this->commandBus->handle(new GetRequestsRequest($this->filters));

        $events = $this->commandBus->handle(new GetEventsCalendarRequest($listRequest));

        return $this->render('Request/management.html.twig', [
            'requestForm' => $form->createView(),
            'listRequest' => $listRequest,
            'request' => $request,
            'events' => $events
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

    public function edit(string $requestId): Response
    {
        $this->init();

        try {
            $request = $this->commandBus->handle(
                new FindRequestRequest($requestId)
            );

        } catch (Exception $exception) {
            throw new RequestIdNotExistsException($requestId);
        }
        $form = $this->requestForm($request, 'update_management_request');

        return $this->renderRequest($form, $request);
    }

    public function update(Request $request): Response
    {
        $this->init();

        $form = $this->requestForm(null, 'update_management_request');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $request = $this->commandBus->handle(new UpdateStatusRequestRequest(
                    $form->getData()['id'],
                    $form->getData()['statusRequest']->id()->value()
                ));
                $this->get('session')->getFlashBag()->add('ok',
                    $this->translator->trans('messages.save.ok'));

            } catch (Exception $exception) {

                $this->get('session')->getFlashBag()->add('error',
                    $this->translator->trans('request.data.not.complete'));

                throw new InvalidArgumentException(sprintf($this->translator->trans('request.controller.update.form.error'),
                    $exception->getMessage()));

            }
        }

        return new RedirectResponse($this->urlGenerator->generate('management_request'));
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        if ($this->isGranted('ROLE_COMPANY_HEAD')
        ) {
            $this->filters += ['department' => $user->departments()->id()->value()];
            $this->filters += ['roles' => 'ROLE_USER'];
        }
    }

}
