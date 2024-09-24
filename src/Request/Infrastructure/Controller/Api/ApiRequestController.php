<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Request\Infrastructure\Controller\Api;


use Exception;
use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TFM\HolidaysManagement\Request\Application\Api\GetApiRequestsRequest;
use TFM\HolidaysManagement\Request\Application\Api\RequestResponse;
use TFM\HolidaysManagement\Request\Application\Create\CreateRequestRequest;
use TFM\HolidaysManagement\Request\Application\Update\UpdateRequestRequest;
use TFM\HolidaysManagement\StatusRequest\Application\Api\GetApiStatusRequestsRequest;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequestRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetTypeRequestRequest;

final class ApiRequestController extends AbstractController
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/"), name="api_get_requests", methods={"GET"})
     * @return JsonResponse
     */
    public function requests(): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                []
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("type/{type}"), name="api_get_requests_by_type_request", methods={"GET"})
     * @param string $type
     * @return JsonResponse
     */

    public function byTypeRequest(string $type): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                [
                    'typesRequest' => $type
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{company}", name="api_get_request_by_company", methods={"GET"})
     * @param $company
     * @return JsonResponse
     */
    public function getByCompany($company): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                [
                    'company' => $company,
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("{id}", name="api_get_one_request", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("workplace/{workPlace}", name="api_get_request_by_work_places", methods={"GET"})
     * @param $workPlace
     * @return JsonResponse
     */
    public function ByWorkPlace($workPlace): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                [
                    'workPlace' => $workPlace,
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("status/{status}", name="api_get_request_by_status", methods={"GET"})
     * @param $status
     * @return JsonResponse
     */
    public function ByStatus($status): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiRequestsRequest(
                [
                    'status' => $status,
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("set", name="api_set_department", methods={"POST"})
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public function set(Request $request): JsonResponse
    {

        $typeRequest = $this->commandBus->handle(new GetTypeRequestRequest($request->get('typesRequest')));
        $statusRequest = $this->commandBus->handle(new GetApiStatusRequestsRequest(['id' => $request->get('statusRequest')]));

//        @TODO: Crashing Documents

        $request = $this->commandBus->handle(
            new CreateRequestRequest(
                $request->get('description'),
                $this->getUser()->id()->value(),
                $typeRequest->id()->value(),
                $statusRequest[0]->id(),
                new \DateTimeImmutable($request->get('requestPeriodStart')),
                new \DateTimeImmutable($request->get('requestPeriodEnd')),
                null
            )
        );

        return new JsonResponse(RequestResponse::fromRequest($request));

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("update/{id}", name="api_update_request", methods={"PUT"})
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(string $id, Request $request): JsonResponse
    {

        $typeRequest = $this->commandBus->handle(new GetTypeRequestRequest($request->get('typesRequest')));
        $statusRequest = $this->commandBus->handle(new GetStatusRequestRequest($request->get('statusRequest')));

        $request = $this->commandBus->handle(new UpdateRequestRequest(
            $id,
            $request->get('description'),
            $this->getUser()->id()->value(),
            $typeRequest,
            $statusRequest,
            new \DateTimeImmutable($request->get('requestPeriodStart')),
            new \DateTimeImmutable($request->get('requestPeriodEnd')),
        ));


        return new JsonResponse(RequestResponse::fromRequest($request));

    }

}
