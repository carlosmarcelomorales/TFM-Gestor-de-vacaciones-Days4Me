<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;

final class TypeRequestResponse implements JsonSerializable
{
    private string $id;
    private string $name;

    private function __construct(TypeRequest $typeRequest)
    {
        $this->id = $typeRequest->id()->value();
        $this->name = $typeRequest->name();

    }

    public function id(): string
    {
        return $this->id;
    }


    public function name(): string
    {
        return $this->name;
    }

    public static function fromArray(array $typesRequests): array
    {
        $typeRequestArray = [];

        foreach ($typesRequests as $typeRequest) {
            $typeRequestArray[] = new self($typeRequest);
        }

        return $typeRequestArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'description' => $this->name(),
        ];
    }
}
