<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class GetWorkplaceByCompany
{
    private WorkPlaceRepository $workplaceRepository;

    public function __construct(WorkPlaceRepository $workplaceRepository)
    {
        $this->workplaceRepository = $workplaceRepository;
    }

    public function __invoke(IdentUuid $companyId) : array
    {
        return $this->workplaceRepository->getByCompany($companyId);
    }
}
