<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;

final class StatusRequestResponse implements JsonSerializable
{
    private string $id;
    private string $name;

    private function __construct(StatusRequest $statusRequest)
    {
        $this->id = $statusRequest->id()->value();
        $this->name = $statusRequest->name();

    }

    public function id(): string
    {
        return $this->id;
    }


    public function name(): string
    {
        return $this->name;
    }

    public static function fromArray(array $statusRequests): array
    {
        $statusRequestArray = [];

        foreach ($statusRequests as $statusRequest) {
            $statusRequestArray[] = new self($statusRequest);
        }

        return $statusRequestArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'description' => $this->name(),
        ];
    }
}
