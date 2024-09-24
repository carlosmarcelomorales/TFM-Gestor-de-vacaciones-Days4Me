<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Domain\Exception;

use Exception;

final class HolidayAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('Holiday with name "%s" already exists', $name)
        );
    }
}
