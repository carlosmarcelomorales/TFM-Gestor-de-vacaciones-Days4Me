<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Find;


final class FindDepartmentRequest
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
