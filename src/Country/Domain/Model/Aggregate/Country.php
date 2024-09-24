<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class Country
{
    private IdentUuid $id;
    private string $name;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection $regions;
    private Collection $companies;

    private function __construct(
        IdentUuid $id,
        string $name,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        Collection $regions
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->regions = $regions;
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

    public function regions(): Collection
    {
        return $this->regions;
    }

    protected function toPrimitives(): callable
    {
        return static function (Country $countries) {
            return [
                $countries->name(),
                $countries->id()
            ];
        };
    }
}
