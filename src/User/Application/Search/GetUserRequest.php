<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Search;

final class GetUserRequest
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
