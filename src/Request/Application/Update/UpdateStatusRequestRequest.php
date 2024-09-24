<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Update;

final class UpdateStatusRequestRequest
{
    private string $id;
    private string $statusRequest;

    public function __construct(
        string $id,
        string $statusRequest
    ) {
        $this->id = $id;
        $this->statusRequest = $statusRequest;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function statusRequest(): string
    {
        return $this->statusRequest;
    }
}
