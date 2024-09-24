<?php

declare(strict_types = 1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\ValueObject\Role;
use Throwable;

final class UserNotHasRoleException extends Exception
{
    public function __construct(IdentUuid $userId, Role $role, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User identified by "%s" does not has the role "%s"', $userId, $role),
            $code,
            $previous
        );
    }
}
