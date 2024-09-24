<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class GetWorkPlace
{
    private WorkPlaceRepository $workplaceRepository;

    public function __construct(WorkPlaceRepository $workplaceRepository)
    {
        $this->workplaceRepository = $workplaceRepository;
    }

    public function __invoke(GetWorkPlaceRequest $getWorkPlaceRequest): WorkPlace
    {
        return $this->workplaceRepository->ofIdOrFail(new IdentUuid($getWorkPlaceRequest->id()));
    }
}
