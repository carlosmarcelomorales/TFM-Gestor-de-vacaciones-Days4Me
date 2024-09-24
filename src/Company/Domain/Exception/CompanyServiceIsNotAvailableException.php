<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Domain\Exception;

use Exception;

class CompanyServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Company service is not available'
        );
    }
}
