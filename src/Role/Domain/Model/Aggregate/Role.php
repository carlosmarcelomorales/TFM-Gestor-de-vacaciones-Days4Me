<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Domain\Model\Aggregate;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class Role
{
    private IdentUuid $id;
    private string $name;
    private ?string $description;
    private ?DateTimeImmutable $createdOn;
    private ?DateTimeImmutable $updatedOn;
    private Collection  $users;
    private Collection $companies;

    public const DEFAULT_ROLE = 'ROLE_USER';
    public const DEFAULT_COMPANY_ADMIN_ROLE = 'ROLE_COMPANY_ADMIN';
    public const DEFAULT_COMPANY_HEAD_ROLE = 'ROLE_COMPANY_HEAD';
    public const DEFAULT_SUPER_ADMIN_ROLE = 'ROLE_SUPER_ADMIN';


    public function __construct(
        IdentUuid $id,
        string $name,
        ?string $description,
        ?DateTimeImmutable $createdOn,
        ?DateTimeImmutable $updatedOn
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdOn = $createdOn ?: new DateTimeImmutable();
        $this->updatedOn = $updatedOn;
        $this->companies = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function id(): IdentUuid
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

    public function createdOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function updatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }

    public static function toArray(Role $roles): array
    {
        $roleArray = [];

        foreach ($roles as $role) {
            $roleArray[] = $role;
        }

        return $roleArray;
    }
}
