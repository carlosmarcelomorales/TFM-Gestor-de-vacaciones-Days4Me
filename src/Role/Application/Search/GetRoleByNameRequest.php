<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;

final class GetRoleByNameRequest
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
