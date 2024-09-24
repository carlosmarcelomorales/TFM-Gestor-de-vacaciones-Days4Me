<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\ValueObject;

use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

final class Password
{
    private string $password;

    public function __construct(string $password)
    {
        $this->guardPasswordIsValid($password);
        $this->password = $password;
    }

    public function toString(): string
    {
        return $this->password;
    }

    public function equals(self $other): bool
    {
        return $this->password === $other->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    private function guardPasswordIsValid(string $value): void
    {
        if (!preg_match(User::REGEX_PASSWORD, $value)) {
            throw new \InvalidArgumentException();
        }
    }
}
