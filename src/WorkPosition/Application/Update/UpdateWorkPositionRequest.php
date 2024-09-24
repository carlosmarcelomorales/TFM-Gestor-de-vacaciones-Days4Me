<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Update;


use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;

final class UpdateWorkPositionRequest
{
    private string $id;
    private string $name;
    private bool $headDepartment;
    private Department $department;

    public function __construct(string $id, string $name, bool $headDepartment, Department $department)
    {
        $this->id = $id;
        $this->name = $name;
        $this->headDepartment = $headDepartment;
        $this->department = $department;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function headDepartment(): bool
    {
        return $this->headDepartment;
    }

    public function department(): Department
    {
        return $this->department;
    }
}
