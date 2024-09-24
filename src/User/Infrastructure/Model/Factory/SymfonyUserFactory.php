<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Model\Factory;

use DateTimeImmutable;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Factory\UserFactory;
use TFM\HolidaysManagement\User\Domain\Model\ValueObject\Password;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

final class SymfonyUserFactory implements UserFactory
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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
        ?DateTimeImmutable $incorporationDate,
        string $streetName,
        int $number,
        string $floor,
        ?PostalCode $postalCode
    ): User {

        $user = User::create(
            IdentUuid::generate(),
            $name,
            $lastName,
            $dni,
            $availableDays,
            $accumulatedDays,
            $socialSecurityNumber,
            $phoneNumber,
            $emailAddress,
            $password,
            $workPosition,
            $department,
            $companies,
            $incorporationDate,
            $streetName,
            $number,
            $floor,
            $postalCode
        );

        $encodedPassword = $this->symfonyFactoryEncodePassword($user);
        $user->setPassword($encodedPassword);
        return $user;

    }

    public function symfonyFactoryEncodePassword(User $user): string
    {
        $encodedPassword = $this
            ->passwordEncoder
            ->encodePassword(
                $user,
                $user->password()
            );

        return $this->buildPasswordWithoutConstructor($encodedPassword)->toString();
    }

    private function buildPasswordWithoutConstructor(string $encodedPassword): Password
    {
        return unserialize(
            'O:' . strlen(Password::class) . ':"' . Password::class . '":1:{s:' . strlen(
                'password'
            ) . ':"' . 'password";s:' . strlen($encodedPassword) . ':"' . $encodedPassword . '";}'
        );
    }
}
