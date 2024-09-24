<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Shared\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

class IdentUuid
{
    private string $value;

    public function __construct(string $value = null)
    {
        $this->value = $value ?? Uuid::uuid4()->toString();
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self();
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}
