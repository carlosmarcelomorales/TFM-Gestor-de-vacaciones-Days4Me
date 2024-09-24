<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use Throwable;

final class UserUpdateAvailableDaysException extends Exception
{
    public function __construct(string $id, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User with email has not "%s" updated', $id),
            $code,
            $previous
        );
    }
}
