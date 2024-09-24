<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Find;


use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class FindDepartment
{
    private DepartmentRepository $repository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->repository = $departmentRepository;
    }

    public function __invoke(FindDepartmentRequest $departmentRequest): Department
    {
        return $this->repository->getDepartmentById(new IdentUuid($departmentRequest->id()));
    }
}
