<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Exception;

use Exception;

final class RequestNotAvailableDaysException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Not enough days or outside the range of holidays allowed by your center!!'
        );
    }
}
