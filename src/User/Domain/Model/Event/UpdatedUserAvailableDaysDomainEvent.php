<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Event;


use DateTimeImmutable;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class UpdatedUserAvailableDaysDomainEvent implements DomainEvent
{
    private IdentUuid $aggregateId;
    private string $emailUser;
    private Department $departments;
    private int $availableDays;
    private int $accumulatedDays;
    private string $status;
    private DateTimeImmutable $occurredOn;

    public function __construct(
        IdentUuid $aggregateId,
        string $emailUser,
        Department $departments,
        int $availableDays,
        int $accumulatedDays,
        string $status,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->emailUser = $emailUser;
        $this->departments = $departments;
        $this->availableDays = $availableDays;
        $this->accumulatedDays = $accumulatedDays;
        $this->status = $status;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): IdentUuid
    {
        return $this->aggregateId;
    }

    public function emailUser(): string
    {
        return $this->emailUser;
    }

    public function departments(): Department
    {
        return $this->departments;
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
        return  $this->occurredOn;
    }
}
