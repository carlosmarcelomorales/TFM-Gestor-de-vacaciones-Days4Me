<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain;

use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface DepartmentRepository
{
    public function save(Department $department): void;

    public function getAllByWorkplace(?array $workplaces): array;

    public function getAllDepartments(array $orderBy): ?array;

    public function getDepartmentById(IdentUuid $id): ?Department;

    public function allFiltered(array $filters): array;
}
