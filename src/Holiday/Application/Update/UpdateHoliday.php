<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Update;


use DateTimeImmutable;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayIdNotExistsException;
use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class UpdateHoliday
{
    private HolidayRepository $repository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->repository = $holidayRepository;
    }

    public function __invoke(UpdateHolidayRequest $holidayRequest): Holiday
    {
        $holiday = $this->repository->getHolidayById(new IdentUuid($holidayRequest->id()));

        if ($holiday === null) {
            throw new HolidayIdNotExistsException($holidayRequest->id());
        }

        $holiday->update(
            $holidayRequest->holidayName(),
            $holidayRequest->startDay(),
            $holidayRequest->endDay(),
            $holidayRequest->workPlace(),
            new DateTimeImmutable()
        );

        $this->repository->save($holiday);

        return $holiday;
    }
}
