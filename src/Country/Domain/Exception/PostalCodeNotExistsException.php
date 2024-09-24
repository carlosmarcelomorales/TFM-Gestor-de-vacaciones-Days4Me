<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Domain\Exception;

use Exception;

final class PostalCodeNotExistsException extends Exception
{
    public function __construct(string $postalCode)
    {
        parent::__construct(
            sprintf('Postal code "%s" not exists', $postalCode)
        );
    }
}
