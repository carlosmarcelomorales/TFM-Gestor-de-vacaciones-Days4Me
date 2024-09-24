<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Event;


use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEvent;

final class RequestTokenDomainEvent implements DomainEvent
{
    private string $emailUser;
    private string $token;
    private ?string $route;
    private DateTimeImmutable $occurredOn;

    public function __construct(
        string $emailUser,
        string $token,
        ?string $route,
        DateTimeImmutable $occurredOn
    ) {
        $this->emailUser = $emailUser;
        $this->token = $token;
        $this->route = $route;
        $this->occurredOn = $occurredOn;
    }

    public function emailUser(): string
    {
        return $this->emailUser;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function route(): ?string
    {
        return $this->route;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return  $this->occurredOn;
    }
}