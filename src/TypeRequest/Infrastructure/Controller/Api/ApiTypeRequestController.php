<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Infrastructure\Controller\Api;

use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use TFM\HolidaysManagement\TypeRequest\Application\Api\GetApiTypesRequestRequest;

final class ApiTypeRequestController extends AbstractController
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/"), name="api_get_type_request", methods={"GET"})
     * @return JsonResponse
     */
    public function requests(): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiTypesRequestRequest(
                []
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("name/{name}"), name="api_get_type_request_by_name", methods={"GET"})
     * @param string $name
     * @return JsonResponse
     */
    public function byTypeRequest(string $name): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiTypesRequestRequest(
                [
                    'name' => $name
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("{id}", name="api_get_one_type_request", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiTypesRequestRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($requests);
    }

}