<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Search;


use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class GetWorkPositionByDepartment
{

    private WorkPositionRepository $workPositionRepository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->workPositionRepository = $workPositionRepository;
    }

    public function __invoke(?array $departments): array
    {
        return $this->workPositionRepository->getAllByDepartment($departments);
    }
}
