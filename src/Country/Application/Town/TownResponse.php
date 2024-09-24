<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town;

use JsonSerializable;
use TFM\HolidaysManagement\Country\Application\PostalCode\PostalCodeResponse;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Town\Town;

class TownResponse implements JsonSerializable
{
    private string $id;

    private string $name;

    private string $regionName;

    private string $regionId;

    private array $postalCodes;

    private string $countryName;

    private function __construct(Town $town)
    {
        $this->id = $town->id();
        $this->name = $town->name();
        $this->regionName = $town->regions()->name();
        $this->regionId = $town->regions()->id();
        $this->postalCodes = PostalCodeResponse::fromArray($town->postalCodes()->toArray());
        $this->countryName = $town->regions()->countries()->name();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function regionName(): string
    {
        return $this->regionName;
    }

    public function regionId(): string
    {
        return $this->regionId;
    }

    public function postalCodes(): array
    {
        return $this->postalCodes;
    }

    public function countryName(): string
    {
        return $this->countryName;
    }

    public static function fromTown(Town $town): self
    {
        return new self($town);
    }

    public static function fromArray(array $towns): array
    {
        $elements = [];

        foreach ($towns as $town) {
            $elements[] = new self($town);
        }

        return $elements;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'region_name' => $this->regionName(),
            'region_id' => $this->regionId(),
            'country_name' => $this->countryName(),
        ];
    }
}
