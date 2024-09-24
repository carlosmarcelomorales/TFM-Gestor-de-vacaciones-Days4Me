<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain;

use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Town\Town;

interface TownRepository
{
    public function AllRegions(array $orderBy, array $limit): ?array;

    public function byId(string $id): ?Town;

    public function findByPostalCode(string $postalCodeId): array;

    public function allFilteredSortedAndLimited(array $filters, array $orderBy, array $limit): array;

    public function allFiltered(array $filters): array;

}
