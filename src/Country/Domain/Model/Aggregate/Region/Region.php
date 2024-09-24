<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Country;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;


class Region
{
    private IdentUuid $id;
    private string $name;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Country $countries;
    private Collection $towns;

    private function __construct(
        IdentUuid $id,
        string $name,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        Country $countries,
        Collection $towns
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->countries = $countries;
        $this->towns = $towns ;
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

    public function countries(): Country
    {
        return $this->countries;
    }

    public function towns(): Collection
    {
        return $this->towns;
    }
}
