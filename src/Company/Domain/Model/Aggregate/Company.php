<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Company\Application\Update\UpdateCompanyRequest;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class Company
{
    const MAX_LENGTH_VAT = 150;
    const MAX_LENGTH_NAME = 150;
    const MAX_LENGTH_DESCRIPTION = 150;
    const MAX_LENGTH_WEBSITE = 150;
    const MAX_LENGTH_EMAIL = 150;
    const MAX_LENGTH_PHONE = 150;

    const REGEX_VAT = '/^((AT)?U[0-9]{8}|(BE)?0[0-9]{9}|(BG)?[0-9]{9,10}|(CY)?[0-9]{8}L|↵
(CZ)?[0-9]{8,10}|(DE)?[0-9]{9}|(DK)?[0-9]{8}|(EE)?[0-9]{9}|↵
(EL|GR)?[0-9]{9}|(ES)?[0-9A-Z][0-9]{7}[0-9A-Z]|(FI)?[0-9]{8}|↵
(FR)?[0-9A-Z]{2}[0-9]{9}|(GB)?([0-9]{9}([0-9]{3})?|[A-Z]{2}[0-9]{3})|↵
(HU)?[0-9]{8}|(IE)?[0-9]S[0-9]{5}L|(IT)?[0-9]{11}|↵
(LT)?([0-9]{9}|[0-9]{12})|(LU)?[0-9]{8}|(LV)?[0-9]{11}|(MT)?[0-9]{8}|↵
(NL)?[0-9]{9}B[0-9]{2}|(PL)?[0-9]{10}|(PT)?[0-9]{9}|(RO)?[0-9]{2,10}|↵
(SE)?[0-9]{12}|(SI)?[0-9]{8}|(SK)?[0-9]{10})$/';

    private IdentUuid $id;
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
    private ?PostalCode $postalCodes;
    private bool $blocked;
    private ?DateTimeImmutable $blockedOn;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection $roles;
    private bool $businessDays;

    public function __construct(
        IdentUuid $id,
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
        ?PostalCode $postalCodes,
        bool $blocked,
        bool $businessDays,
        ?DateTimeImmutable $blockedOn,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
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
        $this->postalCodes = $postalCodes;
        $this->blocked = $blocked;
        $this->businessDays = $businessDays;
        $this->blockedOn = $blockedOn;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->roles = new ArrayCollection();
    }

    public function id(): IdentUuid
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

    public function postalCodes(): PostalCode
    {
        return $this->postalCodes;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function blockedOn(): ?DateTimeImmutable
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

    public function roles(): Collection
    {
        return $this->roles;
    }

    public function __toString()
    {
        return $this->name() . PHP_EOL;
    }

    public function isUpdated(): bool
    {
        return $this->updatedOn() !== null;
    }

    public function businessDays(): bool
    {
        return $this->businessDays;
    }


    public function update(UpdateCompanyRequest $updateCompanyRequest, PostalCode $postalCodes)
    {
        $this->vat = $updateCompanyRequest->vat();
        $this->name = $updateCompanyRequest->name();
        $this->description = $updateCompanyRequest->description();
        $this->webSite = $updateCompanyRequest->webSite();
        $this->phoneNumber1 = $updateCompanyRequest->phoneNumber1();
        $this->phoneNumber2 = $updateCompanyRequest->phoneNumber2();
        $this->email = $updateCompanyRequest->email();
        $this->streetName = $updateCompanyRequest->streetName();
        $this->number = $updateCompanyRequest->number();
        $this->floor = $updateCompanyRequest->floor();
        $this->postalCodes = $postalCodes;
        $this->blocked = $updateCompanyRequest->isBlocked();
        $this->blockedOn = $updateCompanyRequest->isBlocked() ? new DateTimeImmutable() : null;
        $this->businessDays = $updateCompanyRequest->businessDays();
        $this->updatedOn = new DateTimeImmutable();
    }
}
