<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

class ValidateTokenRequest
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }
}
