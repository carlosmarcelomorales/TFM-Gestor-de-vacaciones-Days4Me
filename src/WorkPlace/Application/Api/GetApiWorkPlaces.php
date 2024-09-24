<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Api;

use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class GetApiWorkPlaces
{
    private WorkPlaceRepository $repository;

    public function __construct(WorkPlaceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiWorkPlacesRequest $request): array
    {
        $workPlaces = $this->repository->allFiltered($request->filters());
        return WorkPlaceResponse::fromArray($workPlaces);
    }
}