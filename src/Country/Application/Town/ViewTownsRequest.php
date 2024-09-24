<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town;

final class ViewTownsRequest
{
    private array $filters;


    public function __construct(array $filters = null)
    {
        $this->filters = $filters ?? [];
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
