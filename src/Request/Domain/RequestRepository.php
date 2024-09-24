<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain;


use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface RequestRepository
{
    public function save(Request $request): void;

    public function getAllByUser(IdentUuid $id): ?array;

    public function getRequestById(IdentUuid $id): ?Request;

    public function ofIdOrFail(IdentUuid $id): Request;

    public function allFiltered(array $filters): ?array;

    public function countStatus(array $filters): ? int;
}
