<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Domain\Exception;


use Exception;

final class WorkPositionIdNotExistsException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('WorkPosition with id "%s" not exists', $id)
        );
    }
}
