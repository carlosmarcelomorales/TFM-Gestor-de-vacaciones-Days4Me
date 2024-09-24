<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Find;


use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class FindWorkPosition
{
    private WorkPositionRepository $repository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->repository = $workPositionRepository;
    }

    public function __invoke(FindWorkPositionRequest $workPositionsRequest): WorkPosition
    {
        return $this->repository->getWorkPositionById(new IdentUuid($workPositionsRequest->id()));
    }
}
