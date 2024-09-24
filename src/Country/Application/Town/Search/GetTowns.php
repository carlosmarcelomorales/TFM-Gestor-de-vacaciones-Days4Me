<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town\Search;


use TFM\HolidaysManagement\Country\Application\Town\TownResponse;
use TFM\HolidaysManagement\Country\Domain\TownRepository;

final class GetTowns
{
    private TownRepository $townRepository;

    public function __construct(TownRepository $townRepository)
    {
        $this->townRepository = $townRepository;
    }

    public function __invoke(GetTownsRequest $request) : array
    {
        $towns = $this->townRepository->allFiltered(
            $request->filters()
        );

        return TownResponse::fromArray($towns);
    }
}
