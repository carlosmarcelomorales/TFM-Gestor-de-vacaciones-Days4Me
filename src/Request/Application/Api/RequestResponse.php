<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Api;

use DateTimeImmutable;
use JsonSerializable;
use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;

final class RequestResponse implements JsonSerializable
{
    private string $id;
    private string $description;
    private DateTimeImmutable $requestPeriodStart;
    private DateTimeImmutable $requestPeriodEnd;
    private string $typeRequest;
    private string $statusRequest;

    private function __construct(Request $request)
    {
        $this->id = $request->id()->value();
        $this->description = $request->description();
        $this->typeRequest = $request->typesRequest()->name();
        $this->statusRequest = $request->statusRequest()->name();
        $this->requestPeriodStart = $request->requestPeriodStart();
        $this->requestPeriodEnd = $request->requestPeriodEnd();

    }

    public function id(): string
    {
        return $this->id;
    }


    public function description(): string
    {
        return $this->description;
    }


    public function requestPeriodStart(): DateTimeImmutable
    {
        return $this->requestPeriodStart;
    }


    public function requestPeriodEnd(): DateTimeImmutable
    {
        return $this->requestPeriodEnd;
    }


    public function typeRequest(): string
    {
        return $this->typeRequest;
    }


    public function statusRequest(): string
    {
        return $this->statusRequest;
    }


    public static function fromArray(array $requests): array
    {
        $requestArray = [];

        foreach ($requests as $request) {
            $requestArray[] = new self($request);
        }

        return $requestArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'description' => $this->description(),
            'request_period_start' => $this->requestPeriodStart(),
            'request_period_end' => $this->requestPeriodEnd(),
            'type' => $this->typeRequest(),
            'status' => $this->statusRequest()
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request);
    }
}
