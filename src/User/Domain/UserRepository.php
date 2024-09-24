<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findEmail(string $email): ?User;

    public function ofIdOrFail(IdentUuid $id): ?User;

    public function findOneByEmail(string $email): ?User;

    public function ofFilteredOrNull(array $filter): User;

    public function ofTokenOrFail(string $token): ?User;

    public function allFilters(array $filters): ?array;

}
