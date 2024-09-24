<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Domain\Model\Aggregate;

use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;

abstract class Aggregate
{
    private DomainEvent $eventStream;

    public function pullDomainEvents(): array
    {
        $events = $this->eventStream ?: [];
        $this->eventStream = [];

        return $events;
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->eventStream[] = $event;
    }
}
