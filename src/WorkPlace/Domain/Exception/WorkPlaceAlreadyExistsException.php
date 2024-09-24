<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain\Exception;


use Exception;

final class WorkPlaceAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('WorkPlace "%s" already exists', $name)
        );
    }
}
