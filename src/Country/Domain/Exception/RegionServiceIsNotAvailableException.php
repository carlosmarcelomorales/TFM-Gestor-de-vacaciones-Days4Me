<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Exception;

use Exception;

class RegionServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Region service is not available'
        );
    }
}
