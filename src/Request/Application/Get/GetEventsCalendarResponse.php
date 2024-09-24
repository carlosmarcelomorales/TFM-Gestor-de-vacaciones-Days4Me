<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;

use JsonSerializable;
use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;

final class GetEventsCalendarResponse implements JsonSerializable
{
    private string $id;
    private string $description;
    private \DateTimeImmutable $startDate;
    private \DateTimeImmutable $endDate;
    private string $status;
    private string $typesRequest;
    private string $userName;

    public function __construct(Request $request)
    {
        $this->id = $request->id()->value();
        $this->description = $request->description();
        $this->startDate = $request->requestPeriodStart();
        $this->endDate = $request->requestPeriodEnd();
        $this->status = $request->statusRequest()->name();
        $this->typesRequest = $request->typesRequest()->name();

        $this->userName = $request->users()->name() . " " . $request->users()->lastName();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function typesRequest(): string
    {
        return $this->typesRequest;
    }

    public function jsonSerialize()
    {
        return [
            'id'                => $this->id(),
            'title'             => $this->description(),
            'start'             => $this->startDate()->format('Y-m-d'),
            'description'       => $this->userName,
            'end'               => $this->endDate()->format('Y-m-d'),
            'status'            => $this->status(),
            'typesRequest'      => $this->typesRequest(),
            'backgroundColor'   => $this->getBackgroundColor()
        ];
    }

    private function getBackgroundColor()
    {

        if ($this->status() == "Pending") {
            return "#ffc107";
        }

        if ($this->status() == "Accepted") {
            return "green";
        }

        if ($this->status() == "Declined") {
            return "#dc3545";
        }
    }
}
