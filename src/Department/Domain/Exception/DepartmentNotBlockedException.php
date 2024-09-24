<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain\Exception;


use Exception;

final class DepartmentNotBlockedException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message
        );
    }
}
