<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Application\Search;


final class GetArrayRolesByIdRequest
{
    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
