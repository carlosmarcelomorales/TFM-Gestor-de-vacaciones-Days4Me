<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Event;

use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class UserRegisteredDomainEvent implements DomainEvent
{
    private IdentUuid $aggregateId;

    private string $email;

    private array $roles;

    private DateTimeImmutable $occurredOn;

    public function __construct(
        IdentUuid $aggregateId,
        string $email,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->email = $email;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): IdentUuid

    {
        return $this->aggregateId;
    }

    public function email(): string
    {
        return $this->email;
    }
    
    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
