<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Get;

use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;

final class GetHolidaysByRange
{
    private HolidayRepository $repository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->repository = $holidayRepository;
    }

    public function __invoke(GetHolidaysByRangeRequest $holidayRequest): array
    {
        return $this->repository->rangeHolidaysToRequest($holidayRequest->filters());
    }
}
