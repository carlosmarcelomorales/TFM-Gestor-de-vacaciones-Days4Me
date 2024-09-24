<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Update;


use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;

final class UpdateWorkPlaceRequest
{
    private string $id;
    private string $name;
    private ?string $description;
    private string $phoneNumber1;
    private ?string $phoneNumber2;
    private string $email;
    private bool $permitAccumulate;
    private ?int $monthPermittedToAccumulate;
    private DateTimeImmutable $holidayStartYear;
    private DateTimeImmutable $holidayEndYear;
    private string $streetName;
    private ?int $number;
    private ?string $floor;
    private bool $blocked;
    private ?DateTimeImmutable $blockedOn;
    private string $company;
    private string $postalCode;
    private Collection $holidays;

    public function __construct(
        string $id,
        string $name,
        ?string $description,
        string $phoneNumber1,
        ?string $phoneNumber2 ,
        string $email,
        bool $permitAccumulate,
        ?int $monthPermittedToAccumulate,
        DateTimeImmutable $holidayStartYear,
        DateTimeImmutable $holidayEndYear,
        string $streetName,
        ?int $number,
        ?string $floor,
        bool $blocked,
        ?DateTimeImmutable $blockedOn,
        string $company,
        string $postalCode
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->phoneNumber1 = $phoneNumber1;
        $this->phoneNumber2 = $phoneNumber2;
        $this->email = $email;
        $this->permitAccumulate = $permitAccumulate;
        $this->monthPermittedToAccumulate = $monthPermittedToAccumulate;
        $this->holidayStartYear = $holidayStartYear;
        $this->holidayEndYear = $holidayEndYear;
        $this->streetName = $streetName;
        $this->number = $number;
        $this->floor = $floor;
        $this->blocked = $blocked;
        $this->blockedOn = $blockedOn;
        $this->company = $company;
        $this->postalCode = $postalCode;
        $this->holidays = new ArrayCollection();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function phoneNumber1(): string
    {
        return $this->phoneNumber1;
    }

    public function phoneNumber2(): ?string
    {
        return $this->phoneNumber2;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function permitAccumulate(): bool
    {
        return $this->permitAccumulate;
    }

    public function monthPermittedToAccumulate(): ?int
    {
        return $this->monthPermittedToAccumulate;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }

    public function blockedOn(): ?DateTimeImmutable
    {
        return $this->blockedOn;
    }

    public function company(): string
    {
        return $this->company;
    }

    public function holidayStartYear(): DateTimeImmutable
    {
        return $this->holidayStartYear;
    }

    public function holidayEndYear(): DateTimeImmutable
    {
        return $this->holidayEndYear;
    }

    public function streetName(): string
    {
        return $this->streetName;
    }

    public function number(): ?int
    {
        return $this->number;
    }

    public function floor(): ?string
    {
        return $this->floor;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function holidays(): Collection
    {
        return $this->holidays;
    }
}
