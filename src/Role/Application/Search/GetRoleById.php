<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\RoleRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetRoleById
{
    private RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRoleByIdRequest $request): Role
    {
        return $this->repository->findById(new IdentUuid($request->id()));
    }
}
