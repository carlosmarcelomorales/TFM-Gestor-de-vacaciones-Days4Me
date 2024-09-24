<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\WorkPlace\Infrastructure\Controller\Api;


use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Company\Application\Get\GetCompanyRequest;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Api\GetApiWorkPlacesRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Api\WorkPlaceResponse;
use TFM\HolidaysManagement\WorkPlace\Application\Create\CreateWorkPlaceRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Update\UpdateWorkPlaceRequest;

final class ApiWorkPlaceController extends AbstractController
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
     * @Route("/"), name="api_get_status_work_place", methods={"GET"})
     * @return JsonResponse
     */

    public function workPlaces(): JsonResponse
    {
        $work_places = $this->commandBus->handle(
            new GetApiWorkPlacesRequest(
                []
            )
        );
        return new JsonResponse($work_places);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{company}"), name="api_get_status_work_place_by_company", methods={"GET"})
     * @param string $company
     * @return JsonResponse
     */

    public function byCompany(string $company): JsonResponse
    {
        $work_places = $this->commandBus->handle(
            new GetApiWorkPlacesRequest(
                [
                    'name' => $company
                ]
            )
        );
        return new JsonResponse($work_places);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_work_place", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $work_places = $this->commandBus->handle(
            new GetApiWorkPlacesRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($work_places);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("status/{status}", name="api_get_one_work_place_by_status", methods={"GET"})
     * @param string $status
     * @return JsonResponse
     */
    public function ByStatus(string $status): JsonResponse
    {
        $work_places = $this->commandBus->handle(
            new GetApiWorkPlacesRequest(
                [
                    'blocked' => $status,
                ]
            )
        );
        return new JsonResponse($work_places);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_workplace", methods={"POST"})
     * @param $request
     * @return JsonResponse
     */
    public function set(Request $request): JsonResponse
    {

        $company = $this->commandBus->handle(new GetCompanyRequest($request->get('companies')));
        $postalCode = $this->commandBus->handle(new GetPostalCodeRequest($request->get('postalCodes')));

        try {
            $workplace = $this->commandBus->handle(
                new CreateWorkPlaceRequest(
                    $request->get('name'),
                    $request->get('description'),
                    $request->get('phoneNumber1'),
                    $request->get('phoneNumber2'),
                    $request->get('email'),
                    $request->get('permitAccumulate') === 'true' ? true : false,
                    intval($request->get('monthPermittedToAccumulate')),
                    new \DateTimeImmutable($request->get('holidayStartYear')),
                    new \DateTimeImmutable($request->get('holidayEndYear')),
                    $request->get('streetName'),
                    intval($request->get('number')),
                    $request->get('floor'),
                    $request->get('blocked') === 'true' ? true : false,
                    $company,
                    $postalCode
                )
            );
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf($this->translator->trans('workPlace.controller.save.form.error'),
                $exception->getMessage()));
        }


        return new JsonResponse(WorkPlaceResponse::fromWorkPlace($workplace));
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_update_workplace", methods={"PUT"})
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(string $id, Request $request): JsonResponse
    {

        $company = $this->commandBus->handle(new GetCompanyRequest($request->get('companies')));
        $postalCode = $this->commandBus->handle(new GetPostalCodeRequest($request->get('postalCodes')));

        $workplace = $this->commandBus->handle(new UpdateWorkPlaceRequest(
            $id,
            $request->get('name'),
            $request->get('description'),
            $request->get('phoneNumber1'),
            $request->get('phoneNumber2'),
            $request->get('email'),
            $request->get('permitAccumulate') === 'true' ? true : false,
            intval($request->get('monthPermittedToAccumulate')),
            new \DateTimeImmutable($request->get('holidayStartYear')),
            new \DateTimeImmutable($request->get('holidayEndYear')),
            $request->get('streetName'),
            intval($request->get('number')),
            $request->get('floor'),
            $request->get('blocked') === 'true' ? true : false,
            $request->get('blocked') ? new \DateTimeImmutable() : false,
            $company,
            $postalCode
        ));

        return new JsonResponse(WorkPlaceResponse::fromWorkPlace($workplace));
    }

}
