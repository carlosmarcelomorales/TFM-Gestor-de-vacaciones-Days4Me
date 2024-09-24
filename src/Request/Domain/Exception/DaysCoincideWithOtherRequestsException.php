<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Exception;

use Exception;

final class DaysCoincideWithOtherRequestsException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message
        );
    }
}
