<?php
declare(strict_types=1);
namespace TFM\HolidaysManagement\Country\Application\Town;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Town\Town;
use TFM\HolidaysManagement\Country\Domain\TownRepository;

class GetTown
{
    private TownRepository $townRepository;

    public function __construct(TownRepository $townRepository)
    {
        $this->townRepository = $townRepository;;
    }

    public function __invoke(GetTownRequest $getTownRequest): Town
    {
        return $this->townRepository->ById($getTownRequest->id());
    }


}
