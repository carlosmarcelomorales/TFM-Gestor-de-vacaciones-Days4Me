<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain\Model\Aggregate;

use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

class Department
{
    const DEFAULT_DEPARTMENT_NAME = 'My default Department';
    const DEFAULT_DEPARTMENT_DESCRIPTION = 'My default Department';

    private IdentUuid $id;
    private WorkPlace $workPlace;
    private string $name;
    private ?string $description;
    private string $phoneNumber;
    private ?int $phoneExtension;
    private bool $blocked;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        WorkPlace $workPlace,
        string $name,
        ?string $description,
        string $phoneNumber,
        ?int $phoneExtension,
        bool $blocked,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->workPlace = $workPlace;
        $this->name = $name;
        $this->description = $description;
        $this->phoneNumber = $phoneNumber;
        $this->phoneExtension = $phoneExtension;
        $this->blocked = $blocked;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
    }

    public function id(): IdentUuid
    {
        return $this->id;
    }

    public function workPlace(): WorkPlace
    {
        return $this->workPlace;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function phoneExtension(): ?int
    {
        return $this->phoneExtension;
    }

    public function blocked(): bool
    {
        return $this->blocked;
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
        WorkPlace $workPlace,
        string $name,
        ?string $description,
        string $phoneNumber,
        ?int $phoneExtension,
        bool $blocked,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ): self {
        return new self(
            $id,
            $workPlace,
            $name,
            $description,
            $phoneNumber,
            $phoneExtension,
            $blocked,
            $createdOn,
            $updatedOn
        );
    }

    public function __toString()
    {
        return $this->name() . PHP_EOL;
    }

    public function update(
        WorkPlace $workPlace,
        string $name,
        ?string $description,
        string $phoneNumber,
        ?int $phoneExtension,
        bool $blocked,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->workPlace = $workPlace;
        $this->name = $name;
        $this->description = $description;
        $this->phoneNumber = $phoneNumber;
        $this->phoneExtension = $phoneExtension;
        $this->blocked = $blocked;
        $this->updatedOn = $updatedOn;
    }
}

