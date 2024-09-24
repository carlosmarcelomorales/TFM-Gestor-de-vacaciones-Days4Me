<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Get;


final class GetWorkPositionsRequest
{
    private array $filter;

    public function __construct(array $filter)
    {
        $this->filter = $filter ?? [];
    }

    public function filter(): array
    {
        return $this->filter;
    }
}
