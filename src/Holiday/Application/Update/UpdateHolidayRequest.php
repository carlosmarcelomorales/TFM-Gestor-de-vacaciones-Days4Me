<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Update;


use DateTimeImmutable;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

final class UpdateHolidayRequest
{
    private string $id;
    private string $holidayName;
    private DateTimeImmutable $startDay;
    private DateTimeImmutable $endDay;
    private WorkPlace $workPlace;

    public function __construct(
        string $id,
        string $holidayName,
        DateTimeImmutable $startDay,
        DateTimeImmutable $endDay,
        WorkPlace $workPlace
    ) {
        $this->id = $id;
        $this->holidayName = $holidayName;
        $this->startDay = $startDay;
        $this->endDay = $endDay;
        $this->workPlace = $workPlace;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function holidayName(): string
    {
        return $this->holidayName;
    }

    /**
     * @return DateTimeImmutable
     */
    public function startDay(): DateTimeImmutable
    {
        return $this->startDay;
    }

    /**
     * @return DateTimeImmutable
     */
    public function endDay(): DateTimeImmutable
    {
        return $this->endDay;
    }

    /**
     * @return WorkPlace
     */
    public function workPlace(): WorkPlace
    {
        return $this->workPlace;
    }
}