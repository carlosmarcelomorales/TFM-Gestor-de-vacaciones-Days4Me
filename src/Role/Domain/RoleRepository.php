<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Domain;

use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

interface RoleRepository
{

    public function getAll(): ?array;

    public function findByName(string $role): Role;

    public function findById(IdentUuid $id): Role;

    public function getOne(IdentUuid $roleId): Role;

    public function allFiltered(array $filters): array;

    public function ofAllFiltered(array $filter): array;

    public function ofFilteredOrNull(array $filter): Role;
}
