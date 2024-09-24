<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Domain\Exception;


use Exception;

final class DepartmentIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Department with id "%s" not exists', $id)
        );
    }
}
