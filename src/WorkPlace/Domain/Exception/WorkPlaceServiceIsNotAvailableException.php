<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain\Exception;


use Exception;

final class WorkPlaceServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'WorkPlace service is not available'
        );
    }
}
