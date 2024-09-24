<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Update;


use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;

final class UpdateDepartmentRequest
{
    private string $id;
    private string $name;
    private ?string $description;
    private string $phone;
    private ?int $ext;
    private WorkPlace $workPlace;
    private bool $blocked;

    public function __construct(
        string $id,
        string $name,
        ?string $description,
        string $phone,
        ?int $ext,
        WorkPlace $workPlace,
        bool $blocked
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->phone = $phone;
        $this->ext = $ext;
        $this->workPlace = $workPlace;
        $this->blocked = $blocked;
    }

    public function id(): string
    {
        return $this->id;
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

    public function workPlace(): WorkPlace
    {
        return $this->workPlace;
    }

    public function blocked(): bool
    {
        return $this->blocked;
    }
}
