<?php

declare(strict_types = 1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use Throwable;

final class UserAlreadyHasRoleException extends Exception
{
    public function __construct(IdentUuid $userId, Role $role, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User identified by "%s" already has the role "%s"', $userId, $role),
            $code,
            $previous
        );
    }
}
