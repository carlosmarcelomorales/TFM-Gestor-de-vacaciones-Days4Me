<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Model\Event;


use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;

final class CreateRequestDomainEvent implements DomainEvent
{
    private string $aggregateId;
    private string $emailUser;
    private int $availableDays;
    private int $accumulatedDays;
    private string $status;
    private DateTimeImmutable $occurredOn;

    public function __construct(
        string $aggregateId,
        string $emailUser,
        int $availableDays,
        int $accumulatedDays,
        string $status,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->emailUser = $emailUser;
        $this->availableDays = $availableDays;
        $this->accumulatedDays = $accumulatedDays;
        $this->status = $status;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function emailUser(): string
    {
        return $this->emailUser;
    }

    public function availableDays(): int
    {
        return $this->availableDays;
    }

    public function accumulatedDays(): int
    {
        return $this->accumulatedDays;
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
