<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Update;


final class UpdateUserAvailableDaysRequest
{
    private string $id;
    private string $email;
    private int $availableDays;
    private int $accumulatedDays;
    private string $status;

    public function __construct(
        string $id,
        string $email,
        int $availableDays,
        int $accumulatedDays,
        string $status
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->availableDays = $availableDays;
        $this->accumulatedDays = $accumulatedDays;
        $this->status = $status;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function availableDays(): int
    {
        return $this->availableDays;
    }

    public function accumulatedDays(): int
    {
        return $this->accumulatedDays;
    }

    public function status(): string
    {
        return $this->status;
    }
}
