<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class GetWorkPositionByCompany
{

    private WorkPositionRepository $workPositionRepository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->workPositionRepository = $workPositionRepository;
    }

    public function __invoke(GetWorkPositionByCompanyRequest $getWorkPositionRequest): WorkPosition
    {
        return $this->workPositionRepository->ofOneCompanyId(new IdentUuid($getWorkPositionRequest->id()));
    }
}
