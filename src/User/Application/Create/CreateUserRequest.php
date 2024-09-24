<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Create;

use DateTimeImmutable;

final class CreateUserRequest
{

    private string $name;
    private string $lastName;
    private string $dni;
    private int $availableDays;
    private int $accumulatedDays;
    private string $socialSecurityNumber;
    private string $phone;
    private string $emailAddress;
    private string $password;
    private array $roles;
    private string $workPosition;
    private DateTimeImmutable $incorporation;
    private string$streetName;
    private ?int $number;
    private ?string $floor;
    private string $postalCode;
    private string $company;

    public function __construct(
        string $name,
        string $lastName,
        string $dni,
        int $availableDays,
        int $accumulatedDays,
        string $socialSecurityNumber,
        string $phone,
        string $emailAddress,
        string $password,
        array $roles,
        string $workPosition,
        DateTimeImmutable $incorporation,
        string $streetName,
        int $number,
        string $floor,
        string $postalCode,
        string $company
    ) {
        $this->name = $name;
        $this->lastName = $lastName;
        $this->dni = $dni;
        $this->availableDays = $availableDays;
        $this->accumulatedDays = $accumulatedDays;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->phone = $phone;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->roles = $roles;
        $this->workPosition = $workPosition;
        $this->incorporation = $incorporation;
        $this->streetName = $streetName;
        $this->number = $number;
        $this->floor = $floor;
        $this->postalCode = $postalCode;
        $this->company = $company;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function dni(): string
    {
        return $this->dni;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function availableDays(): int
    {
        return $this->availableDays;
    }

    public function accumulatedDays(): int
    {
        return $this->accumulatedDays;
    }

    public function socialSecurityNumber(): string
    {
        return $this->socialSecurityNumber;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function incorporation(): DateTimeImmutable
    {
        return $this->incorporation;
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

    public function roles(): array
    {
        return $this->roles;
    }

    public function workPosition(): string
    {
        return $this->workPosition;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function company(): string
    {
        return $this->company;
    }
}
