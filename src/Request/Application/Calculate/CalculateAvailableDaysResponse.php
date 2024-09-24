<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Calculate;


final class CalculateAvailableDaysResponse
{
    private bool $permitRequest;
    private int $totalDaysAvailable;
    private int $totalDaysAccumulated;
    private int $totalDaysToConsume;

    public function __construct(
        bool $permitRequest,
        int $totalDaysAvailable,
        int $totalDaysAccumulated,
        int $totalDaysToConsume
    ) {
        $this->permitRequest = $permitRequest;
        $this->totalDaysAvailable = $totalDaysAvailable;
        $this->totalDaysAccumulated = $totalDaysAccumulated;
        $this->totalDaysToConsume = $totalDaysToConsume;
    }

    public function permitRequest(): bool
    {
        return $this->permitRequest;
    }

    public function totalDaysAvailable(): int
    {
        return $this->totalDaysAvailable;
    }

    public function totalDaysAccumulated(): int
    {
        return $this->totalDaysAccumulated;
    }

    public function totalDaysToConsume(): int
    {
        return $this->totalDaysToConsume;
    }
}
