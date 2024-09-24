<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Domain\Exception;

use Exception;

final class HolidayServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Holiday service is not available'
        );
    }
}
