<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Get;

final class GetHolidaysRequest
{
    private array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters ?? [];
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
