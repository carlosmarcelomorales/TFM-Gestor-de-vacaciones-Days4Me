<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;

final class CompanyResponse implements JsonSerializable
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
    private ?PostalCode $postalCodes;
    private bool $blocked;

    private function __construct(Company $company)
    {
        $this->id = $company->id()->value();
        $this->vat = $company->vat();
        $this->name = $company->name();
        $this->description = $company->description();
        $this->webSite = $company->webSite();
        $this->phoneNumber1 = $company->phoneNumber1();
        $this->phoneNumber2 = $company->phoneNumber2();
        $this->email = $company->email();
        $this->streetName = $company->streetName();
        $this->number = $company->number();
        $this->floor = $company->floor();
        $this->postalCodes = $company->postalCodes();
        $this->blocked = $company->isBlocked();

    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromArray(array $companies): array
    {
        $companyArray = [];

        foreach ($companies as $company) {
            $companyArray[] = new self($company);
        }

        return $companyArray;
    }

    public function vat(): string
    {
        return $this->vat;
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

    public function postalCodes(): ?PostalCode
    {
        return $this->postalCodes;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'vat' => $this->vat(),
            'name' => $this->name(),
            'description' => $this->description(),
            'website' => $this->webSite(),
            'phoneNumber1' => $this->phoneNumber1(),
            'phoneNumber2' => $this->phoneNumber2(),
            'email' => $this->email(),
            'streetName' => $this->streetName(),
            'number' => $this->number(),
            'floor' => $this->floor(),
            'postalCode' => $this->postalCodes(),
            'blocked' => $this->blocked(),

        ];
    }

    public static function fromCompany(Company $company): self
    {
        return new self($company);
    }
}
