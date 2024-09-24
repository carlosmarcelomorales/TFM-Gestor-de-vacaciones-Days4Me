<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class GetWorkPosition
{

    private WorkPositionRepository $workPositionRepository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->workPositionRepository = $workPositionRepository;
    }

    public function __invoke(GetWorkPositionRequest $getWorkPositionRequest): WorkPosition
    {
        return $this->workPositionRepository->ofId(new IdentUuid($getWorkPositionRequest->id()));
    }
}
