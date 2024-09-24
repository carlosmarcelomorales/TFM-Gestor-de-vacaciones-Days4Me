<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Domain\Exception;

use Exception;

final class CompanyErrorToCreateException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'There was a problem creating the company, please try again later or contact the site administrator'
        );
    }
}
