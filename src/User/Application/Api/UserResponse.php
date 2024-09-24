<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

final class UserResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private string $lastName;
    private string $emailAddress;
    private string $streetName;
    private ?string $socialSecurityNumber;
    private string $postalCodes;
    private ?string $floor;
    private string $phoneNumber;
    private string $companyName;
    private string $departmentName;
    private string $workPositionName;

    private function __construct(User $user)
    {
        $this->id = $user->id()->value();
        $this->name = $user->dni();
        $this->name = $user->name();
        $this->lastName = $user->lastName();
        $this->emailAddress = $user->emailAddress();
        $this->streetName = $user->streetName();
        $this->socialSecurityNumber = $user->socialSecurityNumber();
        $this->postalCodes = $user->postalCodes()->value();
        $this->floor = $user->floor();
        $this->phoneNumber = $user->phoneNumber();
        $this->companyName = $user->companies()->name();
        $this->departmentName = $user->departments()->name();
        $this->workPositionName = $user->workPositions()->name();

    }

    public function id(): string
    {
        return $this->id;
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

    public function streetName(): string
    {
        return $this->streetName;
    }

    public function socialSecurityNumber(): ?string
    {
        return $this->socialSecurityNumber;
    }

    public function postalCodes(): string
    {
        return $this->postalCodes;
    }

    public function floor(): ?string
    {
        return $this->floor;
    }

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function companyName(): string
    {
        return $this->companyName;
    }

    public function departmentName(): string
    {
        return $this->departmentName;
    }

    public function workPositionName(): string
    {
        return $this->workPositionName;
    }

    public static function fromArray(array $users): array
    {
        $userArray = [];

        foreach ($users as $user) {
            $userArray[] = new self($user);
        }

        return $userArray;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'lastName' => $this->lastName(),
            'emailAddress' => $this->emailAddress(),
            'streetName' => $this->streetName(),
            'socialSecurityNumber' => $this->socialSecurityNumber(),
            'postalCodes' => $this->postalCodes(),
            'floor' => $this->floor(),
            'phoneNumber' => $this->phoneNumber(),
            'departmentName' => $this->departmentName(),
            'workPositionName' => $this->workPositionName(),
        ];
    }

    public static function fromUser(User $user): self
    {
        return new self($user);
    }
}
