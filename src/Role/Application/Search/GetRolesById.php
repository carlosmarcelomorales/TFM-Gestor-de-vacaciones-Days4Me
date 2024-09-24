<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\RoleRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetRolesById
{

    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(GetRoleByIdRequest $getRoleById) : Role
    {
        return $this->roleRepository->findById(new IdentUuid($getRoleById->id()));
    }

}
