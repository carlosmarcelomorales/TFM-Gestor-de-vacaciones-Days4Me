<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Find;


final class FindRequestRequest
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
