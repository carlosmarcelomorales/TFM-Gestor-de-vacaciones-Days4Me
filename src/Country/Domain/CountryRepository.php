<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Country;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface CountryRepository
{
    public function allSorted(array $orderBy): array;

    public function ofId(IdentUuid $id): Country;

}
