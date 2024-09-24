<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Domain\Exception;

use Exception;

final class CompanyVatNotExistsException extends Exception
{
    public function __construct(string $vat)
    {
        parent::__construct(
            sprintf('Company with vat "%s" not exists', $vat)
        );
    }
}
