<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class PostalCode
{
    const REGEX_POSTAL_CODE = '/^[0-5][1-9]{3}[0-9]$/';
    const DEFAULT_NOT_POSTAL_CODE = '00000';

    private IdentUuid $id;
    private string $value;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection $towns;

    public function __construct(
        IdentUuid $id,
        string $value,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        Collection $towns
    ) {
        $this->id = $id;
        $this->value = $value;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->towns = $towns;
    }

    public function id(): IdentUuid
    {
        return $this->id;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function towns(): Collection
    {
        return $this->towns;
    }

    public function __toString()
    {
        return $this->value() . PHP_EOL;
    }
}
