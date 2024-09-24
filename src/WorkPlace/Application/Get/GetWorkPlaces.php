<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Get;


use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class GetWorkPlaces
{
    private WorkPlaceRepository $repository;

    public function __construct(WorkPlaceRepository $workPlaceRepository)
    {
        $this->repository = $workPlaceRepository;
    }

    public function __invoke(GetWorkPlacesRequest $request): array
    {
        return $this->repository->allFiltered($request->filters());
    }
}
