<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Create;

use DateTimeImmutable;
use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlace;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlaceRequest;

final class CreateHoliday
{
    private HolidayRepository $repository;
    private FindWorkPlace $getWorkPlace;

    public function __construct(HolidayRepository $repository, FindWorkPlace $getWorkPlace)
    {
        $this->repository = $repository;
        $this->getWorkPlace = $getWorkPlace;

    }

    public function __invoke(CreateHolidayRequest $holidayRequest) : Holiday
    {
        $workPlace = ($this->getWorkPlace)(new FindWorkPlaceRequest($holidayRequest->workPlace()));

        $holiday = Holiday::create(
            new IdentUuid(),
            $holidayRequest->holidayName(),
            $holidayRequest->startDay(),
            $holidayRequest->endDay(),
            $workPlace,
            new DateTimeImmutable(),
            null
        );

        $this->repository->save($holiday);
        return $holiday;
    }
}
