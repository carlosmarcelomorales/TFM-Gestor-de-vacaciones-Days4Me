<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Api;

final class GetApiTypesRequestRequest
{
    private array $filters;

    public function __construct(
        array $filters
    ) {
        $this->filters = $filters ?? [];
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
