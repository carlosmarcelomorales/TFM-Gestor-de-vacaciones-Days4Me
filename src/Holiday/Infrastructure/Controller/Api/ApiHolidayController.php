<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Holiday\Infrastructure\Controller\Api;


use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Holiday\Application\Api\GetApiHolidaysRequest;
use TFM\HolidaysManagement\Holiday\Application\Api\HolidayResponse;
use TFM\HolidaysManagement\Holiday\Application\Api\HolidaysResponse;
use TFM\HolidaysManagement\Holiday\Application\Create\CreateHolidayRequest;
use TFM\HolidaysManagement\Holiday\Application\Update\UpdateHolidayRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlaceRequest;

final class ApiHolidayController extends AbstractController
{

    private CommandBus $commandBus;
    private TranslatorInterface $translator;

    public function __construct(CommandBus $commandBus, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/"), name="api_get_holidays", methods={"GET"})
     * @return JsonResponse
     */
    public function holidays(): JsonResponse
    {
        $holidays = $this->commandBus->handle(
            new GetApiHolidaysRequest(
                []
            )
        );

        return new JsonResponse($holidays);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("name/{name}"), name="api_get_holidays_by_status", methods={"GET"})
     * @param string $name
     * @return JsonResponse
     */

    public function byStatus(bool $name): JsonResponse
    {
        $holidays = $this->commandBus->handle(
            new GetApiHolidaysRequest(
                [
                    'name' => $name
                ]
            )
        );
        return new JsonResponse($holidays);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{company}", name="api_get_holiday_by_company", methods={"GET"})
     * @param $company
     * @return JsonResponse
     */
    public function getByCompany($company): JsonResponse
    {
        $holidays = $this->commandBus->handle(
            new GetApiHolidaysRequest(
                [
                    'company' => $company,
                ]
            )
        );
        return new JsonResponse($holidays);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_holiday", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $holidays = $this->commandBus->handle(
            new GetApiHolidaysRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($holidays);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("workplace/{workPlace}", name="api_get_holiday_by_work_places", methods={"GET"})
     * @param $workPlace
     * @return JsonResponse
     */
    public function getByWorkPlace($workPlace): JsonResponse
    {
        $holidays = $this->commandBus->handle(
            new GetApiHolidaysRequest(
                [
                    'workPlace' => $workPlace,
                ]
            )
        );
        return new JsonResponse($holidays);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_holiday", methods={"POST"})
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public function setHoliday(Request $request): JsonResponse
    {

        $holidayName = $request->get('holidayName');
        $startDay = new DateTimeImmutable($request->get('startDay'));
        $endDay = new DateTimeImmutable($request->get('endDay'));
        $workplaceId = $request->get('workplaces');

//        $workplace = $this->commandBus->handle(new FindWorkPlaceRequest($workplaceId));

        try {
            $holiday = $this->commandBus->handle(new CreateHolidayRequest(
                $holidayName,
                $startDay,
                $endDay,
                $workplaceId
            ));
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(sprintf($this->translator->trans('holiday.controller.save.form.error'),
                $exception->getMessage()));
        }

        return new JsonResponse(HolidaysResponse::fromHoliday($holiday));
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_set_update_holiday", methods={"PUT"})
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public function updateHoliday(string $id, Request $request): JsonResponse
    {

        $holidayName = $request->get('holidayName');
        $startDay = new DateTimeImmutable($request->get('startDay'));
        $endDay = new DateTimeImmutable($request->get('endDay'));
        $workplaceId = $request->get('workplaces');

        $workplace = $this->commandBus->handle(new FindWorkPlaceRequest($workplaceId));

        try {
            $holiday = $this->commandBus->handle(new UpdateHolidayRequest(
                $id,
                $holidayName,
                $startDay,
                $endDay,
                $workplace
            ));
        } catch (Exception $exception) {
            throw new InvalidArgumentException(sprintf($this->translator->trans('holiday.controller.update.form.error'),
                $exception->getMessage()));
        }

        return new JsonResponse(HolidaysResponse::fromHoliday($holiday));

    }

}
