<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Region;

use JsonSerializable;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Region;

final class RegionResponse implements JsonSerializable
{
    private string $id;

    private string $name;

    private string $country;

    private string $countryId;

    private function __construct(Region $region)
    {
        $this->id = $region->id();
        $this->name = $region->name();
        $this->country = $region->countries()->name();
        $this->countryId = $region->countries()->id();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function countryId(): string
    {
        return $this->countryId;
    }

    public static function fromRegion(Region $region): self
    {
        return new self($region);
    }

    public static function fromArray(array $regions): array
    {
        $regiosArray = [];

        foreach ($regions as $region) {
            $regiosArray[] = new self($region);
        }

        return $regiosArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'country_name' => $this->country(),
            'country_id' => $this->countryId(),
        ];
    }
}
