<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Get;


use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class GetWorkPositions
{
    private WorkPositionRepository $repository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->repository = $workPositionRepository;
    }

    public function __invoke(GetWorkPositionsRequest $workPositionsRequest): array
    {
        return $this->repository->allFiltered($workPositionsRequest->filter());
    }
}
