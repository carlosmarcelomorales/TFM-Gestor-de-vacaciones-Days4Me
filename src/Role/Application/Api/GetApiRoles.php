<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Api;

use TFM\HolidaysManagement\Role\Domain\RoleRepository;

final class GetApiRoles
{
    private RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiRolesRequest $request): array
    {
        $roles = $this->repository->allFiltered($request->filters());
        return RoleResponse::fromArray($roles);
    }
}