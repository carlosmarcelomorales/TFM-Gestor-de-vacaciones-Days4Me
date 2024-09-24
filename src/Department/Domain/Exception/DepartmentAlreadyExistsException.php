<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain\Exception;


use Exception;

final class DepartmentAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('Department "%s" already exists', $name)
        );
    }
}
