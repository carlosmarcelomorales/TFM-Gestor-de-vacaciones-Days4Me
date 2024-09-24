<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

use TFM\HolidaysManagement\Role\Domain\RoleRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetArrayRolesById
{

    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(GetArrayRolesByIdRequest $getArrayRolesByIdRequest) : array
    {
        $roles = [];

        foreach ($getArrayRolesByIdRequest->roles() as $role) {
            $roles[] = $this->roleRepository->findById(new IdentUuid($role));
        }

        return $roles;
    }

}
