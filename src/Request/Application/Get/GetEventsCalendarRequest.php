<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


final class GetEventsCalendarRequest
{

    private array $requests;

    public function __construct(array $requests)
    {
        $this->requests = $requests;
    }

    public function requests() : array
    {
        return $this->requests;
    }
}
