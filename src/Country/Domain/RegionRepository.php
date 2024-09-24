<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Region;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface RegionRepository
{

    public function ofId(IdentUuid $id): Region;

    public function allFiltered(array $filters): array;
}
