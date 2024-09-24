<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\PostalCode;

final class GetPostalCodeRequest
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }
}
