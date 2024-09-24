<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Domain;

use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface HolidayRepository
{
    public function save(Holiday $holiday): void;

    public function getAllHolidays(array $orderBy): ?array;

    public function getHolidayById(IdentUuid $id): ?Holiday;

    public function allFiltered(array $filters): array;

    public function rangeHolidaysToRequest(array $filters): array;

    public function ofIdOrNull(IdentUuid $id): Holiday;
}
