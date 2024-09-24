<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

class UpdatePasswordRequest
{
    private string $id;

    private string $password;
    private ?string $email;

    public function __construct(string $id, string $password, ?string $email)
    {
        $this->id = $id;
        $this->password = $password;
        $this->email = $email;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function email(): ?string
    {
        return $this->email;
    }

}
