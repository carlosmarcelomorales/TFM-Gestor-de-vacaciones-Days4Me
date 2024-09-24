<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use Throwable;

final class UserNotExistsException extends Exception
{
    public function __construct(string $id, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User id "%s" no exists', $id),
            $code,
            $previous
        );
    }
}
