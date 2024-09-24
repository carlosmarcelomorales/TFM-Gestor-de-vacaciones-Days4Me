<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Find;


use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class FindWorkPlace
{
    private WorkPlaceRepository $repository;

    public function __construct(WorkPlaceRepository $workPlaceRepository)
    {
        $this->repository = $workPlaceRepository;
    }

    public function __invoke(FindWorkPlaceRequest $request): WorkPlace
    {
        return $this->repository->getWorkPlaceById(new IdentUuid($request->id()));
    }
}
