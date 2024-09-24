<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Domain\Exception;

use Exception;

final class DocumentErrorToUploadException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Could not upload document'
        );
    }
}