<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Town;

use TFM\HolidaysManagement\Country\Domain\TownRepository;

class ViewTowns
{
    private $townRepository;

    public function __construct(TownRepository $townRepository)
    {
        $this->townRepository = $townRepository;
    }

    public function __invoke($request)
    {
        $towns = $this->townRepository->allFilteredSortedAndLimited(
            $request->filters(),
            $request->orderBy(),
            $request->limit()
        );

        return TownResponse::fromArray($towns);
    }
}
