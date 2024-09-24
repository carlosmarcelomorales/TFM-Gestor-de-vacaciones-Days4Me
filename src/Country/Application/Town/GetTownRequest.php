<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town;

class GetTownRequest
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}
