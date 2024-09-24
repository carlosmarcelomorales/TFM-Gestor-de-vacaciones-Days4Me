<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Update;

use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleById;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleByIdRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUser;
use TFM\HolidaysManagement\User\Application\Search\GetUserRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\UserRepository;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPosition;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPositionRequest;

final class UpdateUser
{
    private UserRepository $UserRepository;
    private GetWorkPosition $getWorkPosition;
    private GetPostalCode $findPostalCode;
    private GetUser $getUser;
    private GetRoleById $getRoleById;

    public function __construct(
        UserRepository $UserRepository,
        GetWorkPosition $getWorkPosition,
        GetPostalCode $findPostalCode,
        GetUser $getUser,
        GetRoleById $getRoleById
    ) {

        $this->UserRepository = $UserRepository;
        $this->getWorkPosition = $getWorkPosition;
        $this->findPostalCode = $findPostalCode;
        $this->getUser = $getUser;
        $this->getRoleById = $getRoleById;
    }

    public function __invoke(UpdateUserRequest $userRequest): User
    {
        $user = ($this->getUser)(new GetUserRequest($userRequest->id()));
        $workPosition = ($this->getWorkPosition)(new GetWorkPositionRequest($userRequest->workPosition()));
        $postalCode = ($this->findPostalCode)(new GetPostalCodeRequest($userRequest->postalCode()));

        $user->update($userRequest, $workPosition, $postalCode, $workPosition->departments());

        if ($userRequest->roles() !== $user->roles()) {
            foreach ($user->roles() as $role) {
                $user->delRoles($role);
            }

            foreach ($userRequest->roles() as $role) {
                $addRole = ($this->getRoleById)(new GetRoleByIdRequest($role->id()->value()));
                $user->addRoles($addRole);
            }
        }

        $this->UserRepository->save($user);

        return $user;

    }
}
