<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain\Exception;

use Exception;

final class DocumentServiceIsNotAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Document service is not available'
        );
    }
}
