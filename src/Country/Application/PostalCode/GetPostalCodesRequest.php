<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Application\PostalCode;

final class GetPostalCodesRequest
{
    private string $townId;

    public function __construct($townId)
    {
        $this->townId = $townId;
    }

    public function townId(): string
    {
        return $this->townId;
    }
}
