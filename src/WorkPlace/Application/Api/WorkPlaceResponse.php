<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Api;

use DateTimeImmutable;
use JsonSerializable;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

final class WorkPlaceResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private string $streetName;
    private ?string $description;
    private ?string $floor;
    private string $phoneNumber1;
    private ?string $phoneNumber2;
    private string $companyName;
    private bool $blocked;
    private ?int $number;
    private DateTimeImmutable $holidayStartYear;
    private DateTimeImmutable $holidayEndYear;
    private ?int $monthPermittedToAccumulate;
    private bool $permitAccumulate;

    private function __construct(WorkPlace $workPlace)
    {
        $this->id = $workPlace->id()->value();
        $this->name = $workPlace->name();
        $this->streetName = $workPlace->streetName();
        $this->description = $workPlace->description();
        $this->floor = $workPlace->floor();
        $this->phoneNumber1 = $workPlace->phoneNumber1();
        $this->phoneNumber2 = $workPlace->phoneNumber2();
        $this->companyName = $workPlace->companies()->name();
        $this->blocked = $workPlace->blocked();
        $this->number = $workPlace->number();
        $this->holidayStartYear = $workPlace->holidayStartYear();
        $this->holidayEndYear = $workPlace->holidayEndYear();
        $this->monthPermittedToAccumulate = $workPlace->monthPermittedToAccumulate();
        $this->permitAccumulate = $workPlace->permitAccumulate();
    }

    public function id(): string
    {
        return $this->id;
    }


    public function name(): string
    {
        return $this->name;
    }

    public function streetName(): string
    {
        return $this->streetName;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function floor(): ?string
    {
        return $this->floor;
    }

    public function phoneNumber1(): string
    {
        return $this->phoneNumber1;
    }

    public function phoneNumber2(): ?string
    {
        return $this->phoneNumber2;
    }

    public function companyName(): string
    {
        return $this->companyName;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function Number(): ?int
    {
        return $this->number;
    }

    public function holidayStartYear(): DateTimeImmutable
    {
        return $this->holidayStartYear;
    }

    public function holidayEndYear(): DateTimeImmutable
    {
        return $this->holidayEndYear;
    }

    public function monthPermittedToAccumulate(): ?int
    {
        return $this->monthPermittedToAccumulate;
    }

    public function permitAccumulate(): bool
    {
        return $this->permitAccumulate;
    }


    public static function fromArray(array $workPlaces): array
    {
        $workPlaceArray = [];

        foreach ($workPlaces as $workPlace) {
            $workPlaceArray[] = new self($workPlace);
        }

        return $workPlaceArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'streetName' => $this->streetName(),
            'description' => $this->description(),
            'floor' => $this->floor(),
            'phoneNumber1' => $this->phoneNumber1(),
            'phoneNumber2' => $this->phoneNumber2(),
            'company_name' => $this->companyName(),
            'blocked' => $this->isBlocked(),
            'number' => $this->number(),
            'holiday_start_year' => $this->holidayStartYear(),
            'holiday_end_year' => $this->holidayEndYear(),
            'month_permitted_toAccumulate' => $this->monthPermittedToAccumulate(),
            'permit_accumulate' => $this->permitAccumulate(),

        ];
    }

    public static function fromWorkPlace(WorkPlace $workPlace): self
    {
        return new self($workPlace);
    }


}
