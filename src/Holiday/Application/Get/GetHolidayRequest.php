<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Application\Get;


final class GetHolidayRequest
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
