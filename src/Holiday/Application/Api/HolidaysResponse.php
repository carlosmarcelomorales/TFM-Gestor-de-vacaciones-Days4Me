<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Api;

use DateTimeImmutable;
use JsonSerializable;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;

final class HolidaysResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private DateTimeImmutable $startDay;
    private DateTimeImmutable $endDay;
    private string $workPlaceName;
    private string $companyName;

    private function __construct(Holiday $holiday)
    {
        $this->id = $holiday->id()->value();
        $this->name = $holiday->holidayName();
        $this->startDay = $holiday->startDay();
        $this->endDay = $holiday->endDay();
        $this->workPlaceName = $holiday->workPlaces()->name();
        $this->companyName = $holiday->workPlaces()->companies()->name();
    }


    public function startDay(): DateTimeImmutable
    {
        return $this->startDay;
    }


    public function endDay(): DateTimeImmutable
    {
        return $this->endDay;
    }


    public function workPlaceName(): string
    {
        return $this->workPlaceName;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function companyName(): string
    {
        return $this->companyName;
    }

    public static function fromArray(array $holidays): array
    {
        $holidayArray = [];

        foreach ($holidays as $holiday) {
            $holidayArray[] = new self($holiday);
        }

        return $holidayArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'start_day' => $this->startDay(),
            'end_day' => $this->endDay(),
            'workPlaceName' => $this->workPlaceName(),
            'companyName' => $this->companyName()
        ];
    }

    public static function fromHoliday(Holiday $holiday): self
    {
        return new self($holiday);
    }

}
