<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Create;

use DateTimeImmutable;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleById;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleByIdRequest;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Event\UserCreatedDomainEvent;
use TFM\HolidaysManagement\User\Domain\UserRepository;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPosition;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPositionRequest;

final class CreateUser
{
    private UserRepository $UserRepository;
    private DomainEventBus $eventBus;
    private GetWorkPosition $getWorkPosition;
    private GetPostalCode $findPostalCode;
    private GetRoleById $getRoleById;

    public function __construct(
        UserRepository $UserRepository,
        DomainEventBus $eventBus,
        GetWorkPosition $getWorkPosition,
        GetPostalCode $findPostalCode,
        GetRoleById $getRoleById
    ) {

        $this->UserRepository = $UserRepository;
        $this->eventBus = $eventBus;
        $this->getWorkPosition = $getWorkPosition;
        $this->findPostalCode = $findPostalCode;
        $this->getRoleById = $getRoleById;
    }

    public function __invoke(CreateUserRequest $userRequest): User
    {
        $workPosition = ($this->getWorkPosition)(new GetWorkPositionRequest($userRequest->workPosition()));

        $postalCode = ($this->findPostalCode)(new GetPostalCodeRequest($userRequest->postalCode()));

        $user = User::create(
            IdentUuid::generate(),
            $userRequest->name(),
            $userRequest->lastName(),
            $userRequest->dni(),
            $userRequest->availableDays(),
            $userRequest->accumulatedDays(),
            $userRequest->socialSecurityNumber(),
            $userRequest->phone(),
            $userRequest->emailAddress(),
            $userRequest->password(),
            $workPosition,
            $workPosition->departments(),
            $workPosition->departments()->workPlace()->companies(),
            $userRequest->incorporation(),
            $userRequest->streetName(),
            $userRequest->number(),
            $userRequest->floor(),
            $postalCode
        );

        foreach ($userRequest->roles() as $role) {
            $addRole = ($this->getRoleById)(new GetRoleByIdRequest($role->id()->value()));
            $user->addRoles($addRole);
        }

        $this->UserRepository->save($user);

        $this->eventBus->publish(
            new UserCreatedDomainEvent(
                $user->id(),
                $user->emailAddress(),
                new DateTimeImmutable()
            )
        );

        return $user;
    }
}
