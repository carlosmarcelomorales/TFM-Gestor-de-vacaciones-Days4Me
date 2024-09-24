<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\Region;

use TFM\HolidaysManagement\Country\Domain\RegionRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetRegion
{
    private RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function __invoke(GetRegionRequest $request): RegionResponse
    {
        $region = $this->regionRepository->ofId(new IdentUuid($request->id()));

        return RegionResponse::fromRegion($region);
    }
}
