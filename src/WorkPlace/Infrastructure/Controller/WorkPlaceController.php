<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Infrastructure\Controller;


use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Country\Application\Town\Search\SearchTownRequest;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\WorkPlace\Application\Create\CreateWorkPlaceRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlaceRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Get\GetWorkPlacesRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Update\UpdateWorkPlaceRequest;
use TFM\HolidaysManagement\WorkPlace\Domain\Exception\WorkPlaceIdNotExistsException;
use TFM\HolidaysManagement\WorkPlace\Domain\Exception\WorkPlaceNotBlockedException;
use TFM\HolidaysManagement\WorkPlace\Infrastructure\Form\CreateWorkPlaceType;

final class WorkPlaceController extends AbstractController
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

        $workPlaces = $this->commandBus->handle(new GetWorkPlacesRequest($this->filters));
        return $this->render('WorkPlace/index.html.twig', ['workPlaces' => $workPlaces]);
    }

    public function addWorkPlace(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPlaceForm(null, 'save_workPlace');
        return $this->render('WorkPlace/workplace.html.twig', ['Form' => $form->createView()]);
    }

    public function saveNewWorkPlace(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPlaceForm(null, 'save_workPlace');
        $form->handleRequest($request);
        $postalCode = $request->get('postal_code') ? $request->get('postal_code') : PostalCode::DEFAULT_NOT_POSTAL_CODE;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(
                    new CreateWorkPlaceRequest(
                        $form->getData()['name'],
                        $form->getData()['description'],
                        $form->getData()['phoneNumber1'],
                        $form->getData()['phoneNumber2'],
                        $form->getData()['email'],
                        $form->getData()['permitAccumulate'],
                        intval($form->getData()['monthPermittedToAccumulate']),
                        $form->getData()['holidayStartYear'],
                        $form->getData()['holidayEndYear'],
                        $form->getData()['streetName'],
                        intval($form->getData()['number']),
                        $form->getData()['floor'],
                        $form->getData()['blocked'],
                        $form->getData()['companies'],
                        $postalCode
                    )
                );
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('workPlace.controller.save.form.error'),
                    $exception->getMessage()));
            }
        }

        return $this->render('WorkPlace/workplace.html.twig', ['Form' => $form->createView()]);
    }

    private function workPlaceForm($workPlace, string $route): FormInterface
    {
        return $this->createForm(CreateWorkPlaceType::class, $workPlace,
            ['method' => 'POST', 'action' => $this->generateUrl($route)]);
    }

    public function editWorkPlace(string $workPlaceId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $workPlace = $this->commandBus->handle(new FindWorkPlaceRequest($workPlaceId));

        } catch (Exception $exception) {
            throw new WorkPlaceIdNotExistsException($workPlaceId);
        }
        $form = $this->workPlaceForm($workPlace, 'update_workPlace');

        $positionInfo = [
            'postalCode' => $this->getUser()->postalCodes()->value(),
            'town'       => $this->getUser()->postalCodes()->towns()[0]->name(),
            'region'    => $this->getUser()->postalCodes()->towns()[0]->regions()->name(),
            'country'    => $this->getUser()->postalCodes()->towns()[0]->regions()->countries()->name()
        ];

        return $this->render(
            'WorkPlace/workplace.html.twig',
            [
                'Form'         => $form->createView(),
                'workPlace'    => $workPlace,
                'positionInfo'  => $positionInfo
            ]);
    }

    public function updateWorkPlace(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->workPlaceForm(null, 'update_workPlace');
        $form->handleRequest($request);

        $postalCode = $request->get('postal_code') ? $request->get('postal_code') : PostalCode::DEFAULT_NOT_POSTAL_CODE;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new UpdateWorkPlaceRequest(
                    $form->getData()['id'],
                    $form->getData()['name'],
                    $form->getData()['description'],
                    $form->getData()['phoneNumber1'],
                    $form->getData()['phoneNumber2'],
                    $form->getData()['email'],
                    $form->getData()['permitAccumulate'],
                    intval($form->getData()['monthPermittedToAccumulate']),
                    $form->getData()['holidayStartYear'],
                    $form->getData()['holidayEndYear'],
                    $form->getData()['streetName'],
                    intval($form->getData()['number']),
                    $form->getData()['floor'],
                    $form->getData()['blocked'] ? $form->getData()['blocked'] : false,
                    $form->getData()['blocked'] ? new DateTimeImmutable() : null,
                    $form->getData()['companies']->id()->value(),
                    $postalCode
                ));
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (WorkPlaceIdNotExistsException $exception) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());

            } catch (WorkPlaceNotBlockedException $exception) {
                $this->get('session')->getFlashBag()->add('warning', $exception->getMessage());

            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('workPlace.controller.update.form.error'),
                    $exception->getMessage()));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('list_workPlace'));
    }

    public function townByPostalCode(Request $request): JsonResponse
    {

        try {
            $postalCodeId = $request->request->get('postalCodeId');
            $town = $this->commandBus->handle(new SearchTownRequest($postalCodeId));
            return new JsonResponse($town);

        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), 200);
        }
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        if ($this->isGranted('ROLE_USER')) {
            $this->filters += ['id' => $user->departments()->workplace()->id()->value()];
        }

    }
}
