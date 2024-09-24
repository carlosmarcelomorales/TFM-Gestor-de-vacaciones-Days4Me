<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Update;

use TFM\HolidaysManagement\Company\Domain\CompanyRepository;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Country\Application\Town\GetTown;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class UpdateCompany
{
    private CompanyRepository $companyRepository;
    private GetPostalCode $findPostalCodeService;
    private GetTown $getTownService;

    public function __construct(
        CompanyRepository $companyRepository,
        GetPostalCode $findPostalCodeService,
        GetTown $getTownService
    ) {
        $this->companyRepository = $companyRepository;
        $this->findPostalCodeService = $findPostalCodeService;
        $this->getTownService = $getTownService;
    }

    public function __invoke(UpdateCompanyRequest $updateCompanyRequest): Company
    {
        $company = $this->companyRepository->ofIdOrFail(new IdentUuid($updateCompanyRequest->id()));

        $postalCode = ($this->findPostalCodeService)(new GetPostalCodeRequest($updateCompanyRequest->postalCode()));

        $company->update(
            $updateCompanyRequest,
            $postalCode
        );

        $this->companyRepository->save($company);

        return $company;
    }
}
