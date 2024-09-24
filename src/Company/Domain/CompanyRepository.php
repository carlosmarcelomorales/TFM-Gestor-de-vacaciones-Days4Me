<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Domain;


use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

interface CompanyRepository
{
    public function AllCompanies(array $orderBy): ?array;

    public function ofIdOrFail(IdentUuid $id): ?Company;

    public function ofUserOrFail(User $user): ?Company;

    public function ofVatOrFail(string $vat): ?Company;

    public function save(Company $company): void;

    public function allFiltered(array $filters): array;
}
