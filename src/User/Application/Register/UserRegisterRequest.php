<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Register;

use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;

final class UserRegisterRequest
{
    private string $emailAddress;
    private string $password;
    private string $name;
    private Company $company;

    public function __construct(
        string $emailAddress,
        string $name,
        string $password,
        Company $company
    ) {
        $this->emailAddress = $emailAddress;
        $this->name = $name;
        $this->password = $password;
        $this->company = $company;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function company(): Company
    {
        return $this->company;
    }
}
