<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Exception;

use Exception;

class CountryServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Country service is not available'
        );
    }
}
