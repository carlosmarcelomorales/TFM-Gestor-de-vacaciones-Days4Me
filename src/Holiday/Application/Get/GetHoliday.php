<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Get;

use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetHoliday
{
    private HolidayRepository $repository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->repository = $holidayRepository;
    }

    public function __invoke(GetHolidayRequest $holidayRequest): Holiday
    {
        return $this->repository->ofIdOrNull(new IdentUuid($holidayRequest->id()));
    }
}
