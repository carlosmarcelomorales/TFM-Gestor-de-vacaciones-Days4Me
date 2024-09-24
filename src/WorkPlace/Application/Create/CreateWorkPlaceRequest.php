<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Create;


use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;


final class CreateWorkPlaceRequest
{
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
    private Company $company;
    private string $postalCode;
    private Collection $holidays;

    public function __construct(
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
        Company $company,
        string $postalCode
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
        $this->company = $company;
        $this->postalCode = $postalCode;
        $this->holidays = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function phoneNumber1(): string
    {
        return $this->phoneNumber1;
    }

    /**
     * @return string|null
     */
    public function phoneNumber2(): ?string
    {
        return $this->phoneNumber2;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function permitAccumulate(): bool
    {
        return $this->permitAccumulate;
    }

    /**
     * @return int/null
     */
    public function monthPermittedToAccumulate(): ?int
    {
        return $this->monthPermittedToAccumulate;
    }

    /**
     * @return bool
     */
    public function blocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @return Company
     */
    public function company(): Company
    {
        return $this->company;
    }

    /**
     * @return DateTimeImmutable
     */
    public function holidayStartYear(): DateTimeImmutable
    {
        return $this->holidayStartYear;
    }

    /**
     * @return DateTimeImmutable
     */
    public function holidayEndYear(): DateTimeImmutable
    {
        return $this->holidayEndYear;
    }

    /**
     * @return string
     */
    public function streetName(): string
    {
        return $this->streetName;
    }

    /**
     * @return int|null
     */
    public function number(): ?int
    {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function floor(): ?string
    {
        return $this->floor;
    }

    /**
     * @return string
     */
    public function postalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @return Collection
     */
    public function holidays(): Collection
    {
        return $this->holidays;
    }
}
