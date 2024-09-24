<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Exception;

use Exception;

final class TokenNotExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            sprintf('Token not exists')
        );
    }
}
