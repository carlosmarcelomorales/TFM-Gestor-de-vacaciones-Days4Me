<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class WorkPlace
{
    const DEFAULT_WORK_PLACE_NAME='My default Work Place';
    const DEFAULT_WORK_PLACE_DESCRIPTION='My default Work Place';

    private IdentUuid $id;
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
    private Company $companies;
    private PostalCode $postalCodes;
    private Collection $holidays;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        string $name,
        ?string $description,
        string $phoneNumber1,
        ?string $phoneNumber2,
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
        Company $companies,
        PostalCode $postalCodes,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
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
        $this->companies= $companies;
        $this->postalCodes = $postalCodes;
        $this->holidays = new ArrayCollection();
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

    public function holidayStartYear(): DateTimeImmutable
    {
        return $this->holidayStartYear;
    }

    public function holidayEndYear(): DateTimeImmutable
    {
        return $this->holidayEndYear;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }

    public function blockedOn(): ?DateTimeImmutable
    {
        return $this->blockedOn;
    }

    public function companies(): Company
    {
        return $this->companies;
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

    public function postalCodes(): PostalCode
    {
        return $this->postalCodes;
    }

    public function holidays(): Collection
    {
        return $this->holidays;
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
        ?string $description,
        string $phoneNumber1,
        ?string $phoneNumber2,
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
        Company $companies,
        PostalCode $postalCodes,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ): self {
        return new self(
            $id,
            $name,
            $description,
            $phoneNumber1,
            $phoneNumber2,
            $email,
            $permitAccumulate,
            $monthPermittedToAccumulate,
            $holidayStartYear,
            $holidayEndYear,
            $streetName,
            $number,
            $floor,
            $blocked,
            $blockedOn,
            $companies,
            $postalCodes,
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
        ?string $description,
        string $phoneNumber1,
        ?string $phoneNumber2,
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
        Company $companies,
        PostalCode $postalCodes,
        ?DateTimeImmutable $updatedOn
    ) {
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
        $this->companies = $companies;
        $this->postalCodes = $postalCodes;
        $this->updatedOn = $updatedOn;
    }
}
