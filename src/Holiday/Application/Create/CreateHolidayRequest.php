<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Create;


use DateTimeImmutable;

final class CreateHolidayRequest
{
    private string $holidayName;
    private DateTimeImmutable $startDay;
    private DateTimeImmutable $endDay;
    private string $workPlace;

    public function __construct(
        string $holidayName,
        DateTimeImmutable $startDay,
        DateTimeImmutable $endDay,
        string $workPlace
    ) {
        $this->holidayName = $holidayName;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->workPlace = $workPlace;
    }

    public function holidayName(): string
    {
        return $this->holidayName;
    }

    public function startDay(): DateTimeImmutable
    {
        return $this->startDay;
    }

    public function endDay(): DateTimeImmutable
    {
        return $this->endDay;
    }

    public function workPlace(): string
    {
        return $this->workPlace;
    }
}
