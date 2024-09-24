<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Domain\Exception;


use Exception;

final class WorkPositionAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('WorkPosition "%s" already exists', $name)
        );
    }
}
