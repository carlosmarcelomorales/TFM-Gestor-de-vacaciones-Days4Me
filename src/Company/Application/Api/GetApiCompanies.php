<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Api;

use TFM\HolidaysManagement\Company\Domain\CompanyRepository;

final class GetApiCompanies
{
    private CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiCompaniesRequest $request): array
    {
        $companies = $this->repository->allFiltered($request->filters());
        return CompanyResponse::fromArray($companies);
    }
}