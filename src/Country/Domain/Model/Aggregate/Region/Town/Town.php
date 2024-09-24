<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Town;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Region;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class Town
{
    const DEFAULT_NOT_TOWN = 'UNKNOWN';

    private IdentUuid $id;
    private string $name;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Region $regions;
    private Collection $postalCodes;

    private function __construct(
        IdentUuid $id,
        string $name,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        Region $regions,
        Collection $postalCodes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->regions = $regions;
        $this->postalCodes = $postalCodes;
    }

    public function id(): string
    {
        return $this->id->value();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function regions(): Region
    {
        return $this->regions;
    }

    public function postalCodes(): Collection
    {
        return $this->postalCodes;
    }
}
