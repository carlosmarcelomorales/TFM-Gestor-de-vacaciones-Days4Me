<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Api;

use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;

final class GetApiHolidays
{
    private HolidayRepository $repository;

    public function __construct(HolidayRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiHolidaysRequest $request): array
    {
        $holidays = $this->repository->allFiltered($request->filters());
        return HolidaysResponse::fromArray($holidays);
    }
}