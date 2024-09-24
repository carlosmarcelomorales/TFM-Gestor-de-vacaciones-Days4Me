<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Domain\Model\Event;

interface DomainEventBus
{
    public function publish(DomainEvent ...$event): void;
}
