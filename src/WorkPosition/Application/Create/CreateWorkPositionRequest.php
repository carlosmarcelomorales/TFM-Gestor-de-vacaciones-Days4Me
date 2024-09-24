<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Create;


use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;

final class CreateWorkPositionRequest
{
    private string $name;
    private bool $headDepartment;
    private Department $department;

    public function __construct(string $name, bool $headDepartment, Department $department)
    {
        $this->name = $name;
        $this->headDepartment = $headDepartment;
        $this->department = $department;
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
