<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Update;

final class UpdateCompanyRequest
{
    private string $id;
    private string $vat;
    private string $name;
    private ?string $description;
    private ?string $webSite;
    private string $phoneNumber1;
    private ?string $phoneNumber2;
    private string $email;
    private string $streetName;
    private ?int $number;
    private ?string $floor;
    private string $postalCode;
    private bool $blocked;
    private bool $businessDays;


    public function __construct(
        string $id,
        string $vat,
        string $name,
        ?string $description,
        ?string $webSite,
        string $phoneNumber1,
        ?string $phoneNumber2,
        string $email,
        string $streetName,
        ?int $number,
        ?string $floor,
        string $postalCode,
        bool $blocked,
        bool $businessDays
    ) {
        $this->id = $id;
        $this->vat = $vat;
        $this->name = $name;
        $this->description = $description;
        $this->webSite = $webSite;
        $this->phoneNumber1 = $phoneNumber1;
        $this->phoneNumber2 = $phoneNumber2;
        $this->email = $email;
        $this->streetName = $streetName;
        $this->number = $number;
        $this->floor = $floor;
        $this->postalCode = $postalCode;
        $this->blocked = $blocked;
        $this->businessDays = $businessDays;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function vat(): string
    {
        return $this->vat;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function webSite(): ?string
    {
        return $this->webSite;
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

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function businessDays(): bool
    {
        return $this->businessDays;
    }
}
