<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Get;

use TFM\HolidaysManagement\Company\Domain\CompanyRepository;

final class GetCompanies
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function __invoke(GetCompaniesRequest $getCompaniesRequest): array
    {
        return $this->companyRepository->allFiltered($getCompaniesRequest->filters());
    }
}