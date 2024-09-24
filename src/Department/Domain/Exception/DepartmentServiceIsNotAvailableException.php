<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain\Exception;

use Exception;

final class DepartmentServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Department service is not available'
        );
    }
}
