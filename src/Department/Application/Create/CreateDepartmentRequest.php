<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Create;

final class CreateDepartmentRequest
{
    private string $name;
    private ?string $description;
    private string $phone;
    private ?int $ext;
    private string $workplace;
    private bool $blocked;

    public function __construct(
        string $name,
        ?string $description,
        string $phone,
        ?int $ext,
        string $workplace,
        bool $blocked
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->phone = $phone;
        $this->ext = $ext;
        $this->workplace = $workplace;
        $this->blocked = $blocked;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function ext(): ?int
    {
        return $this->ext;
    }

    public function workPlace(): string
    {
        return $this->workplace;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }
}
