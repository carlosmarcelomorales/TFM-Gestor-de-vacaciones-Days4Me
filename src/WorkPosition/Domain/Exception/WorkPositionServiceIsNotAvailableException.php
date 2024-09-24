<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Domain\Exception;

use Exception;

final class WorkPositionServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'WorkPosition service is not available'
        );
    }
}
