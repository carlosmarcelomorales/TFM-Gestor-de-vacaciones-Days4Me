<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain\Exception;


use Exception;

final class WorkPlaceNotBlockedException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message
        );
    }
}
