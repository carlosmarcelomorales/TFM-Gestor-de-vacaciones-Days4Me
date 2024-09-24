<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class Document
{
    private IdentUuid $id;
    private string $name;
    private ?string $link;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private ?DateTimeImmutable $deleteOn;
    private Collection $requests;

    public function __construct(
        IdentUuid $id,
        string $name,
        ?string $link,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        ?DateTimeImmutable $deleteOn
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->deleteOn = $deleteOn;
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

    public function link(): string
    {
        return $this->link;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function deleteOn(): ?DateTimeImmutable
    {
        return $this->deleteOn;
    }

    public static function create(
        IdentUuid $id,
        string $name,
        ?string $link,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn,
        ?DateTimeImmutable $deleteOn
    ): self {
        return new self(
            $id,
            $name,
            $link,
            $createdOn,
            $updatedOn,
            $deleteOn
        );
    }
}