<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Model\Event;


use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;

final class UpdateStatusRequestDomainEvent implements DomainEvent
{
    private string $aggregateId;
    private string $userId;
    private string $status;
    private DateTimeImmutable $occurredOn;

    public function __construct(
        string $aggregateId,
        string $userId,
        string $status,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->userId = $userId;
        $this->status = $status;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
