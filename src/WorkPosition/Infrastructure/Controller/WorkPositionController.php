<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Infrastructure\Controller;


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
use TFM\HolidaysManagement\WorkPosition\Application\Create\CreateWorkPositionRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Find\FindWorkPositionRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Get\GetWorkPositionsRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Update\UpdateWorkPositionRequest;
use TFM\HolidaysManagement\WorkPosition\Domain\Exception\WorkPositionIdNotExistsException;
use TFM\HolidaysManagement\WorkPosition\Infrastructure\Form\CreateWorkPositionType;

final class WorkPositionController extends AbstractController
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

        $workPosition = $this->commandBus->handle(new GetWorkPositionsRequest($this->filters));
        return $this->render('WorkPosition/index.html.twig', ['workPositions' => $workPosition]);
    }

    public function addWorkPosition(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPositionForm(null, 'save_workPosition');
        return $this->render('WorkPosition/workposition.html.twig', ['workPositionForm' => $form->createView()]);
    }

    public function saveNewWorkPosition(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPositionForm(null, 'save_workPosition');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(
                    new CreateWorkPositionRequest(
                        $form->getData()['name'],
                        $form->getData()['headDepartment'],
                        $form->getData()['departments']
                    )
                );
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('workPosition.controller.save.form.error'),
                    $exception->getMessage()));
            }
        }

        return $this->render('WorkPosition/workposition.html.twig', ['workPositionForm' => $form->createView()]);
    }

    private function workPositionForm($workPosition, string $route): FormInterface
    {
        return $this->createForm(CreateWorkPositionType::class, $workPosition,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route)
            ]);
    }

    public function editWorkPosition(string $workPositionId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $workPosition = $this->commandBus->handle(
                new FindWorkPositionRequest($workPositionId)
            );

        } catch (Exception $exception) {
            throw new WorkPositionIdNotExistsException($workPositionId);
        }
        $form = $this->workPositionForm($workPosition, 'update_workPosition');

        return $this->render(
            'WorkPosition/workposition.html.twig',
            [
                'workPositionForm' => $form->createView(),
                'workPosition' => $workPosition
            ]);
    }

    public function updateWorkPosition(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPositionForm(null, 'update_workPosition');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new UpdateWorkPositionRequest(
                    $form->getData()['id'],
                    $form->getData()['name'],
                    $form->getData()['headDepartment'] ? $form->getData()['headDepartment'] : false,
                    $form->getData()['departments']
                ));
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (WorkPositionIdNotExistsException $exception) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());

            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('workPosition.controller.update.form.error'),
                    $exception->getMessage()));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('list_workPosition'));

    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

    }
}
