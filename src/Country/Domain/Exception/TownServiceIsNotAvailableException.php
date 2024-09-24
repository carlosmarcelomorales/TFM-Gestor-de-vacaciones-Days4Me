<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Exception;

use Exception;

class TownServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Town service is not available'
        );
    }
}
