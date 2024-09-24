<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

class Request
{
    private IdentUuid $id;
    private string $description;
    private User $users;
    private TypeRequest $typesRequest;
    private StatusRequest $statusRequest;
    private DateTimeImmutable $requestPeriodStart;
    private DateTimeImmutable $requestPeriodEnd;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection $documents;

    public function __construct(
        IdentUuid $id,
        string $description,
        User $users,
        TypeRequest $typesRequest,
        StatusRequest $statusRequest,
        DateTimeImmutable $requestPeriodStart,
        DateTimeImmutable $requestPeriodEnd,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->users = $users;
        $this->typesRequest = $typesRequest;
        $this->statusRequest = $statusRequest;
        $this->requestPeriodStart = $requestPeriodStart;
        $this->requestPeriodEnd = $requestPeriodEnd;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->documents = new ArrayCollection();
    }

    public function id(): IdentUuid
    {
        return $this->id;
    }

    public function users(): User
    {
        return $this->users;
    }

    public function typesRequest(): TypeRequest
    {
        return $this->typesRequest;
    }

    public function statusRequest(): StatusRequest
    {
        return $this->statusRequest;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function requestPeriodStart(): DateTimeImmutable
    {
        return $this->requestPeriodStart;
    }

    public function requestPeriodEnd(): DateTimeImmutable
    {
        return $this->requestPeriodEnd;
    }

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public function documents(): Collection
    {
        return $this->documents;
    }

    public static function create(
        IdentUuid $id,
        string $description,
        User $users,
        TypeRequest $typesRequest,
        StatusRequest $statusRequest,
        DateTimeImmutable $requestPeriodStart,
        DateTimeImmutable $requestPeriodEnd,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ): self {
        return new self(
            $id,
            $description,
            $users,
            $typesRequest,
            $statusRequest,
            $requestPeriodStart,
            $requestPeriodEnd,
            $createdOn,
            $updatedOn
        );
    }

    public function __toString()
    {
        return $this->statusRequest() . PHP_EOL .
            $this->typesRequest() . PHP_EOL .
            $this->description() . PHP_EOL;
    }

    public function update(
        string $description,
        User $users,
        TypeRequest $typesRequest,
        DateTimeImmutable $requestPeriodStart,
        DateTimeImmutable $requestPeriodEnd,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->description = $description;
        $this->users = $users;
        $this->typesRequest = $typesRequest;
        $this->requestPeriodStart = $requestPeriodStart;
        $this->requestPeriodEnd = $requestPeriodEnd;
        $this->updatedOn = $updatedOn;
    }

    public function updateStatus(
        StatusRequest $statusRequest
    ) {
        $this->statusRequest = $statusRequest;
    }

    public function addDocuments(Document $documents): void
    {
        $this->documents->add($documents);
    }

    public function delDocuments(Document $documents): void
    {
        $this->documents->remove($documents);
    }

    public function getDocuments(Document $documents): array
    {
        return $this->documents()->toArray();
    }

}
