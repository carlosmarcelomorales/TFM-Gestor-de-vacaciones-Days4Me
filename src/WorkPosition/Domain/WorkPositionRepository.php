<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Domain;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

interface WorkPositionRepository
{
    public function save(WorkPosition $workPosition): void;

    public function getAllByDepartment(?array $departments): ?array;

    public function ofId(IdentUuid $workPosition): ?WorkPosition;

    public function ofOneCompanyId(IdentUuid $companyId): ?WorkPosition;

    public function getWorkPositionById(IdentUuid $workPosition): ?WorkPosition;

    public function getAllWorkPositions(array $orderBy): ?array;
    
    public function allFiltered(array $filters): array;
}
