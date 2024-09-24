<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Region;

final class GetRegionRequest
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
