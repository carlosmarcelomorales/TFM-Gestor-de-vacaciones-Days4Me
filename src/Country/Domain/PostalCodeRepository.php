<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface PostalCodeRepository
{
    public function ofValueOrFail(string $value): PostalCode;

    public function ofValueOrDefault(string $value): PostalCode;

    public function ofTownId(IdentUuid $townId): array;
}
