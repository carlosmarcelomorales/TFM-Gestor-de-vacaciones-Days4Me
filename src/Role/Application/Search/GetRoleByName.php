<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\RoleRepository;

final class GetRoleByName
{
    private RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRoleByNameRequest $request): Role
    {
        return $this->repository->findByName($request->name());
    }
}
