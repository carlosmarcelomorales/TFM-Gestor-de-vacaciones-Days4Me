<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Api;

use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;

final class GetApiDepartments
{
    private DepartmentRepository $repository;

    public function __construct(DepartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiDepartmentsRequest $request): array
    {
        $departments = $this->repository->allFiltered($request->filters());
        return DepartmentResponse::fromArray($departments);
    }
}