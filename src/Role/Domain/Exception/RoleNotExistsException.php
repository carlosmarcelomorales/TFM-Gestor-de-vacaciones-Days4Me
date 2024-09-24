<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Domain\Exception;

use Exception;

class RoleNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Role "%s" not ', $id)
        );
    }
}
