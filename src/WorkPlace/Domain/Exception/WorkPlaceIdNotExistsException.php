<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Domain\Exception;


use Exception;

final class WorkPlaceIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('WorkPlace "%s" not exists', $id)
        );
    }
}
