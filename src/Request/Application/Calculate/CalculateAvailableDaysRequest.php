<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Calculate;

use DateTimeImmutable;

final class CalculateAvailableDaysRequest
{
    private string $user;
    private DateTimeImmutable $startDateRequest;
    private DateTimeImmutable $endDateRequest;

    public function __construct(
        string $user,
        DateTimeImmutable $startDateRequest,
        DateTimeImmutable $endDateRequest
    ) {
        $this->user = $user;
        $this->startDateRequest = $startDateRequest;
        $this->endDateRequest = $endDateRequest;
    }

    public function user(): string
    {
        return $this->user;
    }

    public function startDateRequest(): DateTimeImmutable
    {
        return $this->startDateRequest;
    }

    public function endDateRequest(): DateTimeImmutable
    {
        return $this->endDateRequest;
    }
}
