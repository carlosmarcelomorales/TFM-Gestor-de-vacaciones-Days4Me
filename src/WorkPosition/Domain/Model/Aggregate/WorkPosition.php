<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate;

use DateTimeImmutable;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class WorkPosition
{
    const DEFAULT_WORK_POSITION_NAME = 'My default WorkPosition';

    private IdentUuid $id;
    private string $name;
    private bool $headDepartment;
    private Department $departments;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        string $name,
        bool $headDepartment,
        Department $departments,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->headDepartment = $headDepartment;
        $this->departments = $departments;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
    }

    public function id(): IdentUuid
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

    public function departments(): Department
    {
        return $this->departments;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public static function create(
        IdentUuid $id,
        string $name,
        bool $headDepartment,
        Department $departments,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ): self {
        return new self(
            $id,
            $name,
            $headDepartment,
            $departments,
            $createdOn,
            $updatedOn
        );
    }

    public function __toString()
    {
        return $this->name() . PHP_EOL;
    }

    public function update(
        string $name,
        bool $headDepartment,
        Department $departments,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->name = $name;
        $this->headDepartment = $headDepartment;
        $this->departments = $departments;
        $this->updatedOn = $updatedOn;
    }
}
