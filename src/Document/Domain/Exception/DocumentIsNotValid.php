<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain\Exception;

use Exception;

final class DocumentIsNotValid extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'The file is not a valid document'
        );
    }
}