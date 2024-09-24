<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Infrastructure\Event;

use Symfony\Component\Messenger\MessageBusInterface;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;

final class MessengerDomainEventBus implements DomainEventBus
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
