<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Domain\Exception;

use Exception;

final class TypeRequestIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Type Request with id "%s" not exists', $id)
        );
    }
}
