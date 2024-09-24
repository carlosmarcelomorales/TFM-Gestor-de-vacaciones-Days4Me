<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town\Search;


final class SearchTownRequest
{
    private string $searchTown;

    public function __construct(string $searchTown)
    {
        $this->searchTown = $searchTown;
    }

    public function searchTown(): string
    {
        return $this->searchTown;
    }
}
