<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class StatusRequest
{

    const PENDING = '2caa591f-603c-4298-86fe-37756bf8138b';
    const ANNULLED = 'c6e0e693-1548-48ce-9d68-cdfde67abe4j';
    const DECLINED = '5190910c-baa4-4f6b-8453-96c30503bb16';
    const ACCEPTED = 'c6e0e693-1548-48ce-9d68-cdfde67abe4h';

    private IdentUuid $id;
    private string $name;
    private Collection $requests;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;

    public function __construct(
        IdentUuid $id,
        string $name,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->requests = new ArrayCollection();
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
