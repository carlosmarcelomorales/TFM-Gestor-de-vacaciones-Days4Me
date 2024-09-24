<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class TypeRequest
{
    private IdentUuid $id;
    private string $name;
    private Collection $requests;
    private bool $discountDays;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        string $name,
        bool $discountDays,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->requests = new ArrayCollection();
        $this->discountDays = $discountDays;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
    }

    public function id(): IdentUuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function discountDays(): bool
    {
        return $this->discountDays;
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

    public function __toString()
    {
        return $this->name() . PHP_EOL;
    }
}
