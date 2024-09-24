<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Exception;

use Exception;

class PostalCodeServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Postal Code service is not available'
        );
    }
}
