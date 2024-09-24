<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Domain\Model\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
}
