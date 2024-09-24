<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Domain;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;

interface TypeRequestRepository
{

    public function ofIdOrFail(IdentUuid $id): ?TypeRequest;

    public function save(TypeRequest $typeRequest): void;

    public function getAll() : array;

    public function allFiltered(array $filters): array;
}
