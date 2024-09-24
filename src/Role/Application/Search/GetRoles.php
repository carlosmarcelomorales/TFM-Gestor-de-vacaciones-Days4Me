<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\RoleRepository;

final class GetRoles
{
    private RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRolesRequest $request): array
    {
        return $this->repository->allFiltered(
            $request->filters()
        );
    }
}
