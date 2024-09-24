<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Application\Search;

final class GetStatusRequestRequest
{
    private string $id;

    public function __construct(
        string $id
    ) {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
