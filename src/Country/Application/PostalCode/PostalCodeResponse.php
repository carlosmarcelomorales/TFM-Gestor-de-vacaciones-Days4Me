<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\PostalCode;

use JsonSerializable;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class PostalCodeResponse implements JsonSerializable
{
    private IdentUuid $id;

    private string $value;

    private function __construct(PostalCode $postalCode)
    {
        $this->id = $postalCode->id();
        $this->value = $postalCode->value();
    }

    public function id(): string
    {
        return $this->id->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function fromArray(array $postalCodes): array
    {
        $postalCodesArray = [];

        foreach ($postalCodes as $postalCode) {
            $postalCodesArray[] = new self($postalCode);
        }

        return $postalCodesArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
