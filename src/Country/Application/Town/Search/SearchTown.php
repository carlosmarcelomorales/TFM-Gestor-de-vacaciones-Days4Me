<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town\Search;

use TFM\HolidaysManagement\Country\Domain\TownRepository;


final class SearchTown
{
    private TownRepository $repository;

    public function __construct(TownRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SearchTownRequest $postalCodeRequest): array
    {
        return $this->repository->findByPostalCode($postalCodeRequest->searchTown());
    }
}