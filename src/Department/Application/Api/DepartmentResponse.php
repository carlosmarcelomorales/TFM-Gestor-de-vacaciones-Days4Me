<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;

final class DepartmentResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private string $description;
    private string $phoneNumber;
    private int $phoneExtension;
    private string $workplace;
    private bool $blocked;

    private function __construct(Department $department)
    {
        $this->id = $department->id()->value();
        $this->name = $department->name();
        $this->description = $department->description();
        $this->phoneNumber = $department->phoneNumber();
        $this->phoneExtension = $department->phoneExtension();
        $this->workplace = $department->workPlace()->id()->value();
        $this->blocked = $department->blocked();

    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }


    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function phoneExtension(): int
    {
        return $this->phoneExtension;
    }

    public function workplace(): string
    {
        return $this->workplace;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }

    public static function fromArray(array $departments): array
    {
        $departmentArray = [];

        foreach ($departments as $department) {
            $departmentArray[] = new self($department);
        }

        return $departmentArray;
    }

    public function jsonSerialize()
    {

        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
            'phoneNumber' => $this->phoneNumber(),
            'phoneExtension' => $this->phoneExtension(),
            'workPlace' => $this->workplace(),
            'blocked' => $this->blocked(),
        ];
    }


    public static function fromDepartment(Department $department): self
    {
        return new self($department);
    }
}
