<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Exception;

use Exception;
use Throwable;

final class TokenNotExistException extends Exception
{
    public function __construct(string $token, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Token "%s" no exists', $token),
            $code,
            $previous
        );
    }
}