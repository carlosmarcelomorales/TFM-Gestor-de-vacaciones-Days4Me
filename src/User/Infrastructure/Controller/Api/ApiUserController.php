<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\User\Infrastructure\Controller\Api;


use Exception;
use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TFM\HolidaysManagement\Role\Application\Search\GetArrayRolesByIdRequest;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Application\Api\GetApiUsersRequest;
use TFM\HolidaysManagement\User\Application\Api\UserResponse;
use TFM\HolidaysManagement\User\Application\Create\CreateUserRequest;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserRequest;

final class ApiUserController extends AbstractController
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/"), name="api_get_user", methods={"GET"})
     * @return JsonResponse
     */

    public function users(): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                []
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{company}"), name="api_get_user_by_company", methods={"GET"})
     * @param string $company
     * @return JsonResponse
     */

    public function byCompany(string $company): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                [
                    'company' => $company
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("work_position/{workPosition}"), name="api_get_user_by_work_position", methods={"GET"})
     * @param string $workPosition
     * @return JsonResponse
     */

    public function byWorkPosition(string $workPosition): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                [
                    'workPosition' => $workPosition
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("work_place/{workPlace}"), name="api_get_user_by_work_place", methods={"GET"})
     * @param string $workPlace
     * @return JsonResponse
     */

    public function byWorkPlace(string $workPlace): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                [
                    'workPlace' => $workPlace
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_user", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($requests);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("dni/{dni}", name="api_get_one_user_by_dni", methods={"GET"})
     * @param string $dni
     * @return JsonResponse
     */
    public function ByDni(string $dni): JsonResponse
    {
        $requests = $this->commandBus->handle(
            new GetApiUsersRequest(
                [
                    'dni' => $dni,
                ]
            )
        );
        return new JsonResponse($requests);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_user", methods={"POST"})
     * @param $request
     * @return JsonResponse
     */
    public function set(Request $request): JsonResponse
    {

        $roles = $this->commandBus->handle(new GetArrayRolesByIdRequest([$request->get('roles')]));

        try {
            $user = $this->commandBus->handle(new CreateUserRequest(
                $request->get('name'),
                $request->get('lastName'),
                $request->get('dni'),
                intval($request->get('availableDays')),
                intval($request->get('accumulatedDays')),
                $request->get('socialSecurityNumber'),
                $request->get('phoneNumber'),
                $request->get('emailAddress'),
                IdentUuid::generate()->value(),
                $roles,
                $request->get('workPositions'),
                new \DateTimeImmutable($request->get('incorporationDate')),
                $request->get('streetName'),
                intval($request->get('number')),
                $request->get('floor'),
                $request->get('postal_code'),
                $request->get('company')
            ));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf('Error creating user. Error : %s', $e->getMessage()));
        }


        return new JsonResponse(UserResponse::fromUser($user));
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_update_user", methods={"PUT"})
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(string $id, Request $request): JsonResponse
    {

        $roles = $this->commandBus->handle(new GetArrayRolesByIdRequest([$request->get('roles')]));

        $user = $this->commandBus->handle(new UpdateUserRequest(
                $id,
                $request->get('name'),
                $request->get('lastName'),
                $request->get('dni'),
                intval($request->get('availableDays')),
                intval($request->get('accumulatedDays')),
                $request->get('socialSecurityNumber'),
                $request->get('phoneNumber'),
                $request->get('emailAddress'),
                $roles,
                $request->get('workPositions'),
                new \DateTimeImmutable($request->get('incorporationDate')),
                $request->get('streetName'),
                intval($request->get('number')),
                $request->get('floor'),
                $request->get('blocked') === 'true' ? true : false,
                $request->get('postal_code'),
                $request->get('company')
            )
        );

        return new JsonResponse(UserResponse::fromUser($user));

    }
}
