<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Shared\Domain\Model\Aggregate\Aggregate;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserRequest;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

class User extends Aggregate implements UserInterface
{
    public const REGEX_PASSWORD = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
    public const REGEX_PHONE = '/^((\+)34|0|0034)[1-9](\d{2}){4}$/';
    const MAX_LENGTH_EMAIL = 150;

    private IdentUuid $id;
    private string $name;
    private string $lastName;
    private string $dni;
    private int $availableDays;
    private int $accumulatedDays;
    private ?string $socialSecurityNumber;
    private string $phoneNumber;
    private string $emailAddress;
    private string $password;
    private Collection $roles;
    private WorkPosition $workPositions;
    private Department $departments;
    private Company $companies;
    private ?DateTimeImmutable $incorporationDate;
    private string $streetName;
    private ?int $number;
    private ?string $floor;
    private PostalCode $postalCodes;
    private bool $blocked;
    private string $tokenRecovery;
    private string $tokenRecoveryValidator;
    private DateTimeImmutable $tokenRecoveryExpirationDate;
    private ?DateTimeImmutable $blockedOn;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection $requests;

    public function __construct(
        IdentUuid $id,
        string $name,
        string $lastName,
        string $dni,
        int $availableDays,
        int $accumulatedDays,
        ?string $socialSecurityNumber,
        string $phoneNumber,
        string $emailAddress,
        string $password,
        WorkPosition $workPositions,
        Department $departments,
        Company $companies,
        ?DateTimeImmutable $incorporationDate,
        string $streetName,
        ?int $number,
        ?string $floor,
        PostalCode $postalCodes,
        bool $blocked,
        ?DateTimeImmutable $blockedOn,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {

        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->dni = $dni;
        $this->availableDays = $availableDays;
        $this->accumulatedDays = $accumulatedDays;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->phoneNumber = $phoneNumber;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->roles = new ArrayCollection();
        $this->workPositions = $workPositions;
        $this->departments = $departments;
        $this->companies = $companies;
        $this->incorporationDate = $incorporationDate;
        $this->streetName = $streetName;
        $this->number = $number;
        $this->floor = $floor;
        $this->postalCodes = $postalCodes;
        $this->blocked = $blocked;
        $this->blockedOn = $blockedOn;
        $this->createdOn = $createdOn ? $createdOn : new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->requests = new ArrayCollection();
    }

    public function id(): IdentUuid
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

    public function dni(): string
    {
        return $this->dni;
    }

    public function availableDays(): int
    {
        return $this->availableDays;
    }

    public function accumulatedDays(): int
    {
        return $this->accumulatedDays;
    }

    public function socialSecurityNumber(): ?string
    {
        return $this->socialSecurityNumber;
    }

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function roles(): Collection
    {
        return $this->roles;
    }

    public function workPositions(): WorkPosition
    {
        return $this->workPositions;
    }

    public function departments(): Department
    {
        return $this->departments;
    }

    public function companies(): Company
    {
        return $this->companies;
    }

    public function incorporationDate(): DateTimeImmutable
    {
        return $this->incorporationDate;
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

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function isBlockedOn(): ?DateTimeImmutable
    {
        return $this->blockedOn;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function requests(): Collection
    {
        return $this->requests;
    }

    public static function create(
        IdentUuid $id,
        string $name,
        string $lastName,
        string $dni,
        int $availableDays,
        int $accumulatedDays,
        string $socialSecurityNumber,
        string $phoneNumber,
        string $emailAddress,
        string $password,
        WorkPosition $workPosition,
        Department $department,
        Company $company,
        ?DateTimeImmutable $incorporationDate,
        string $streetName,
        int $number,
        string $floor,
        PostalCode $postalCode
    ): self {

        return new self(
            $id,
            $name,
            $lastName,
            $dni,
            $availableDays,
            $accumulatedDays,
            $socialSecurityNumber,
            $phoneNumber,
            $emailAddress,
            $password,
            $workPosition,
            $department,
            $company,
            $incorporationDate,
            $streetName,
            $number,
            $floor,
            $postalCode,
            false,
            null,
            null,
            null);
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getPassword()
    {
        return (string)$this->password();
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return (string)$this->emailAddress();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function addRoles(Role $roles): void
    {
        $this->roles->add($roles);
    }

    public function delRoles(Role $roles): void
    {
        $this->roles->removeElement($roles);
    }

    public function getRoles(): array
    {
        $roles[] = Role::DEFAULT_ROLE;

        return array_unique(
            array_map(
                static function (Role $role): string {
                    return $roles[] = strtoupper((string)$role->name());
                },
                $this->roles()->toArray()
            )
        );
    }

    public function generateRecoveryToken(): string
    {
        try {
            $token = bin2hex(random_bytes(16));
        } catch (Exception $e) {
        }

        $this->tokenRecovery = substr($token, 0, 16);
        $this->tokenRecoveryValidator = crypt(substr($token, 16), $this->id()->value());
        $this->tokenRecoveryExpirationDate = new DateTimeImmutable('+24 hours');

        return $token;
    }

    public function isTokenValid(string $token): bool
    {
        $isDateValid = $this->tokenRecoveryExpirationDate !== null && $this->tokenRecoveryExpirationDate > new \DateTimeImmutable();
        $isTokenValid = hash_equals($this->tokenRecoveryValidator, crypt($token, $this->id()->value()));

        return $isDateValid && $isTokenValid;
    }

    public function update(
        UpdateUserRequest $updateUserRequest,
        WorkPosition $workPosition,
        PostalCode $postalCodes,
        Department $departments
    ) {
        $this->name = $updateUserRequest->name();
        $this->lastName = $updateUserRequest->lastName();
        $this->dni = $updateUserRequest->dni();
        $this->availableDays = $updateUserRequest->availableDays();
        $this->accumulatedDays = $updateUserRequest->accumulatedDays();
        $this->socialSecurityNumber = $updateUserRequest->socialSecurityNumber();
        $this->phoneNumber = $updateUserRequest->phone();
        $this->emailAddress = $updateUserRequest->emailAddress();
        $this->workPositions = $workPosition;
        $this->departments = $workPosition->departments();
        $this->companies = $workPosition->departments()->workPlace()->companies();
        $this->incorporationDate = $updateUserRequest->incorporation();
        $this->streetName = $updateUserRequest->streetName();
        $this->number = $updateUserRequest->number();
        $this->floor = $updateUserRequest->floor();
        $this->postalCodes = $postalCodes;
        $this->blocked = $updateUserRequest->blocked();
        $this->blockedOn = $updateUserRequest->blocked() ? new DateTimeImmutable() : null;
        $this->updatedOn = new DateTimeImmutable();
    }

    public function setAvailableDays(int $availableDays): void
    {
        $this->availableDays = $availableDays;
    }

    public function setAccumulatedDays(int $accumulatedDays): void
    {
        $this->accumulatedDays = $accumulatedDays;
    }
}
