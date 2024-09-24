<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Get;


use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;

final class GetDepartments
{
    private DepartmentRepository $repository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->repository = $departmentRepository;
    }

    public function __invoke(GetDepartmentsRequest $departmentsRequest): array
    {
        return $this->repository->allFiltered($departmentsRequest->filters());
    }
}
