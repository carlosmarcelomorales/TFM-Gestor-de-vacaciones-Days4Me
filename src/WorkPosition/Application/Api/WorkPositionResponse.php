<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

final class WorkPositionResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private string $departmentName;
    private bool $isHeadDepartment;

    private function __construct(WorkPosition $workPosition)
    {
        $this->id = $workPosition->id()->value();
        $this->isHeadDepartment = $workPosition->headDepartment();
        $this->name = $workPosition->name();
        $this->departmentName = $workPosition->departments()->name();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }


    public function departmentName(): string
    {
        return $this->departmentName;
    }


    public function isHeadDepartment(): bool
    {
        return $this->isHeadDepartment;
    }


    public static function fromArray(array $workPositions): array
    {
        $workPositionArray = [];

        foreach ($workPositions as $workPosition) {
            $workPositionArray[] = new self($workPosition);
        }

        return $workPositionArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'department_name' => $this->departmentName(),
            'is_head_department' => $this->isHeadDepartment(),
        ];
    }

    public static function fromWorkPosition(WorkPosition $workPosition): self
    {
        return new self($workPosition);
    }

}
