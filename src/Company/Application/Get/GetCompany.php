<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Get;

use TFM\HolidaysManagement\Company\Domain\CompanyRepository;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetCompany
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function __invoke(GetCompanyRequest $getCompanyRequest): Company
    {
        return $this->companyRepository->ofIdOrFail(new IdentUuid($getCompanyRequest->id()));
    }
}