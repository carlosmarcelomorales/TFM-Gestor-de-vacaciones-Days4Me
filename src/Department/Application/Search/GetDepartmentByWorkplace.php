<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Search;

use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Application\Search\GetWorkplaceByCompany;


final class GetDepartmentByWorkplace
{
    private DepartmentRepository $departmentRepository;

    private GetWorkplaceByCompany  $getWorkplaceByCompany;

    public function __construct(
        DepartmentRepository $departmentRepository,
        GetWorkplaceByCompany $getWorkplaceByCompany
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->getWorkplaceByCompany = $getWorkplaceByCompany;
    }

    public function __invoke(IdentUuid $companyId)
    {
        $workplaces = ($this->getWorkplaceByCompany)($companyId);

        return $this->departmentRepository->getAllByWorkplace($workplaces);
    }

}
