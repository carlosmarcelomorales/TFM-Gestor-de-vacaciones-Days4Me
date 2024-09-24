<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use Throwable;

final class UserEmailNotExistsException extends Exception
{
    public function __construct(string $email, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User identified by email "%s" not exists', $email),
            $previous
        );
    }
}
