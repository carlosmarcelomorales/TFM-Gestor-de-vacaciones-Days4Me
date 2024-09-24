<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Api;

use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class GetApiWorkPositions
{
    private WorkPositionRepository $repository;

    public function __construct(WorkPositionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiWorkPositionsRequest $request): array
    {
        $workPlaces = $this->repository->allFiltered($request->filters());
        return WorkPositionResponse::fromArray($workPlaces);
    }
}