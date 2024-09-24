<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Api;

use JsonSerializable;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;

final class RoleResponse implements JsonSerializable
{
    private string $id;
    private string $name;
    private ?string $description;

    private function __construct(Role $role)
    {
        $this->id = $role->id()->value();
        $this->name = $role->name();
        $this->description = $role->description();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function name(): string
    {
        return $this->name;
    }


    public static function fromArray(array $roles): array
    {
        $roleArray = [];

        foreach ($roles as $role) {
            $roleArray[] = new self($role);
        }

        return $roleArray;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
        ];
    }
}
