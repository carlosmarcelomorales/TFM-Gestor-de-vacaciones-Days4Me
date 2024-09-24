<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

final class RequestTokenRequest
{
    private string $email;
    private ?string $id;
    private ?string $route;

    public function __construct(string $email, ?string $id, ?string $route)
    {
        $this->email = $email;
        $this->id = $id;
        $this->route = $route;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function route(): ?string
    {
        return $this->route;
    }
}
