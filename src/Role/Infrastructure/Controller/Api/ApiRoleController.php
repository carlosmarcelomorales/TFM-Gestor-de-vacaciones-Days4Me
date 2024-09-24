<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Infrastructure\Controller\Api;

use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use TFM\HolidaysManagement\Role\Application\Api\GetApiRolesRequest;

final class ApiRoleController extends AbstractController
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/"), name="get_roles", methods={"GET"})
     * @return JsonResponse
     */

    public function roles(): JsonResponse
    {
        $roles = $this->commandBus->handle(
            new GetApiRolesRequest(
                []
            )
        );
        return new JsonResponse($roles);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("name/{name}"), name="get_roles_by_name", methods={"GET"})
     * @param string $name
     * @return JsonResponse
     */

    public function byName(string $name): JsonResponse
    {
        $roles = $this->commandBus->handle(
            new GetApiRolesRequest(
                [
                    'name' => $name
                ]
            )
        );
        return new JsonResponse($roles);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("{id}", name="get_one_role", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $roles = $this->commandBus->handle(
            new GetApiRolesRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($roles);
    }

}