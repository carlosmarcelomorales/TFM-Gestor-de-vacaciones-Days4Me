<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Domain\Exception;

use Exception;

final class RequestIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Request with id "%s" not exists', $id)
        );
    }
}
