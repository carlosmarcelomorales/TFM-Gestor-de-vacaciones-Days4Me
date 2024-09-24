<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Search;

final class GetTypeRequestRequest
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
