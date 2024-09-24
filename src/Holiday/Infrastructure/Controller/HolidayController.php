<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Infrastructure\Controller;

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
use TFM\HolidaysManagement\Holiday\Application\Create\CreateHolidayRequest;
use TFM\HolidaysManagement\Holiday\Application\Get\GetHolidayRequest;
use TFM\HolidaysManagement\Holiday\Application\Get\GetHolidaysRequest;
use TFM\HolidaysManagement\Holiday\Application\Update\UpdateHolidayRequest;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayIdNotExistsException;
use TFM\HolidaysManagement\Holiday\Infrastructure\Form\CreateHolidayType;

final class HolidayController extends AbstractController
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

        $holidays = $this->commandBus->handle(new GetHolidaysRequest($this->filters));
        return $this->render('Holiday/index.html.twig', ['holidays' => $holidays]);
    }

    public function addHoliday(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->holidayForm(null, 'save_holiday');
        return $this->render('Holiday/holiday.html.twig', ['holidayForm' => $form->createView()]);
    }

    public function saveNewHoliday(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->holidayForm(null, 'save_holiday');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new CreateHolidayRequest(
                        $form->getData()['holidayName'],
                        $form->getData()['startDay'],
                        $form->getData()['endDay'],
                        $form->getData()['workplaces']->id()->Value()
                    )
                );
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));
            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('holiday.controller.save.form.error'),
                    $exception->getMessage()));
            }
        }

        return $this->render('Holiday/holiday.html.twig',
            [
                'holidayForm' => $form->createView()
            ]);
    }

    private function holidayForm($holiday, string $route): FormInterface
    {
        return $this->createForm(CreateHolidayType::class, $holiday,
            ['method' => 'POST', 'action' => $this->generateUrl($route)]);
    }

    public function editHoliday(string $holidayId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $holiday = $this->commandBus->handle(
                new GetHolidayRequest($holidayId)
            );

        } catch (Exception $exception) {
            throw new HolidayIdNotExistsException($holidayId);
        }
        $form = $this->holidayForm($holiday, 'update_holiday');

        return $this->render(
            'Holiday/holiday.html.twig',
            [
                'holidayForm' => $form->createView(),
                'holiday' => $holiday
            ]);
    }

    public function updateHoliday(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->holidayForm(null, 'update_holiday');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new UpdateHolidayRequest(
                    $form->getData()['id'],
                    $form->getData()['holidayName'],
                    $form->getData()['startDay'],
                    $form->getData()['endDay'],
                    $form->getData()['workplaces']
                ));
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (HolidayIdNotExistsException $exception) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());
            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('holiday.controller.update.form.error'),
                    $exception->getMessage()));
            }
        }


        return new RedirectResponse($this->urlGenerator->generate('list_holiday'));
    }

    private function init()
    {
        $user = $this->getUser();
        $this->filters = [];

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        if ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_COMPANY_HEAD')) {
            $this->filters += ['workPlace' => $user->departments()->workPlace()->id()->value()];
        }

    }

}
