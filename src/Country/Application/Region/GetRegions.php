<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Region;

use TFM\HolidaysManagement\Country\Domain\RegionRepository;

final class GetRegions
{
    private RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function __invoke(GetRegionsRequest $request): array
    {
        $regions = $this->regionRepository->allFiltered(
            $request->filters()
        );

        return RegionResponse::fromArray($regions);
    }
}
