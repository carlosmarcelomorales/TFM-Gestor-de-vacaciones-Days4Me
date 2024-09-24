<?php

declare(strict_types = 1);

namespace TFM\HolidaysManagement\Role\Domain\Model\Exception;

use Exception;
use Throwable;

final class NotExistsWorkPositionException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'Work Position not exists.',
            $code,
            $previous
        );
    }
}
