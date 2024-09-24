<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Domain;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;

interface StatusRequestRepository
{
    public function ofIdOrFail(IdentUuid $id): ?StatusRequest;

    public function ofId(IdentUuid $id): ?StatusRequest;

    public function save(StatusRequest $statusRequest): void;

    public function allFiltered(array $filters): array;
}
