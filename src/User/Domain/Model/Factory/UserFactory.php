<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Domain\Model\Factory;

use DateTimeImmutable;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

interface UserFactory
{
    public function register(
        string $name,
        string $lastName,
        string $dni,
        int $availableDays,
        int $accumulatedDays,
        string $socialSecurityNumber,
        string $phoneNumber,
        string $emailAddress,
        string $password,
        WorkPosition $workPosition,
        Department $department,
        Company $companies,
        DateTimeImmutable $incorporation,
        string $streetName,
        int $number,
        string $floor,
        ?PostalCode $postalCode
    ): User;

    public function symfonyFactoryEncodePassword(User $user): string;
}
