<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use TFM\HolidaysManagement\User\Domain\Model\ValueObject\Role;
use Throwable;

final class UserExistsException extends Exception
{
    public function __construct(string $email, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User "%s" is already registered', $email),
            $code,
            $previous
        );
    }
}
