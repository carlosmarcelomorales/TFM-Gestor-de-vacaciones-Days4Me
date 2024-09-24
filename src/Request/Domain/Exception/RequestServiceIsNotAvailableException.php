<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Exception;

use Exception;

final class RequestServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Request service is not available'
        );
    }
}
