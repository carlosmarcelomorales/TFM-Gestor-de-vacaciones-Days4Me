<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

interface WorkPlaceRepository
{
    public function save(WorkPlace $workPlace): void;

    public function getByCompany(IdentUuid $companyId): ?array;

    public function getAllWorkPlaces(array $orderBy): ?array;

    public function getWorkPlaceById(IdentUuid $workPlaceId): ?WorkPlace;

    public function allFiltered(array $filters): array;
}
