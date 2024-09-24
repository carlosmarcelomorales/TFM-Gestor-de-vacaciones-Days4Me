<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Country;

use JsonSerializable;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Country;

final class CountryResponse implements JsonSerializable
{
    private string $id;
    private string $name;

    private function __construct(Country $country)
    {
        $this->id = $country->id();
        $this->name = $country->name();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function fromCountry(Country $country): self
    {
        return new self($country);
    }

    public static function fromArray(array $countries): array
    {
        $countryArray = [];

        foreach ($countries as $country) {
            $countryArray[] = new self($country);
        }

        return $countryArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
        ];
    }
}
