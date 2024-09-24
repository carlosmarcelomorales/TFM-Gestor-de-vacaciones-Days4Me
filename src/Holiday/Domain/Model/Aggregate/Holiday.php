<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate;

use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

class Holiday
{
    private IdentUuid $id;
    private string $holidayName;
    private DateTimeImmutable $startDay;
    private DateTimeImmutable $endDay;
    private WorkPlace $workPlaces;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        string $holidayName,
        DateTimeImmutable $startDay,
        DateTimeImmutable $endDay,
        WorkPlace $workPlaces,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->holidayName = $holidayName;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->workPlaces = $workPlaces;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
    }

    public function id(): IdentUuid
    {
        return $this->id;
    }

    public function holidayName(): string
    {
        return $this->holidayName;
    }

    public function startDay(): DateTimeImmutable
    {
        return $this->startDay;
    }

    public function endDay(): DateTimeImmutable
    {
        return $this->endDay;
    }

    public function workPlaces(): WorkPlace
    {
        return $this->workPlaces;
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
        string $holidayName,
        DateTimeImmutable $startDay,
        DateTimeImmutable $endDay,
        WorkPlace $workPlaces,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ): self {
        return new self(
            $id,
            $holidayName,
            $startDay,
            $endDay,
            $workPlaces,
            $createdOn,
            $updatedOn
        );
    }

    public function __toString()
    {
        return $this->holidayName() . PHP_EOL;
    }

    public function update(
        string $holidayName,
        DateTimeImmutable $startDay,
        DateTimeImmutable $endDay,
        WorkPlace $workPlaces,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->holidayName = $holidayName;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->workPlaces = $workPlaces;
        $this->updatedOn = $updatedOn;
    }
}
