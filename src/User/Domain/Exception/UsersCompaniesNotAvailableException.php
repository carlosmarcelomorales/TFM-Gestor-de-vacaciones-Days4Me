<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Exception;

use Exception;

final class UsersCompaniesNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Users service is not available'
        );
    }
}
