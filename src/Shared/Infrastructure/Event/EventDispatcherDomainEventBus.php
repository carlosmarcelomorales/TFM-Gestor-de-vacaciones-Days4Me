<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;

final class EventDispatcherDomainEventBus implements DomainEventBus
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
