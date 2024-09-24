<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Country;

final class GetCountriesRequest
{
    private array $orderBy;

    public function __construct(array $orderBy = null)
    {
        $this->orderBy = $orderBy ?? [];
    }

    public function orderBy(): array
    {
        return $this->orderBy;
    }

}
