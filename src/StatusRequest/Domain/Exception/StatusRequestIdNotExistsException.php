<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Domain\Exception;

use Exception;

final class StatusRequestIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Status Request with id "%s" not exists', $id)
        );
    }
}
