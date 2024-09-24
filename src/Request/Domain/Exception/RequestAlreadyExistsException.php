<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Exception;

use Exception;

final class RequestAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('Request "%s" already exists', $name)
        );
    }
}
