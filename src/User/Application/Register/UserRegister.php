<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Register;

use DateTimeImmutable;
use TFM\HolidaysManagement\Company\Application\Get\GetCompany;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleByName;
use TFM\HolidaysManagement\Role\Application\Search\GetRoleByNameRequest;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\Model\Exception\InvalidRoleException;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Event\UserRegisteredDomainEvent;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserExistsException;
use TFM\HolidaysManagement\User\Domain\Model\Factory\UserFactory;
use TFM\HolidaysManagement\User\Domain\UserRepository;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPosition;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPositionByCompany;
use TFM\HolidaysManagement\WorkPosition\Application\Search\GetWorkPositionByCompanyRequest;

final class UserRegister
{
    private UserRepository $userRepository;
    private DomainEventBus $eventBus;

    private UserFactory   $factory;

    private GetWorkPosition  $getWorkPositionService;
    private GetPostalCode  $findPostalCodeService;
    private GetRoleByName $getRoleByNameService;
    private GetCompany $findCompanyService;

    private GetWorkPositionByCompany $getWorkPositionByCompany;

    public function __construct(
        UserRepository $userRepository,
        DomainEventBus $eventBus,
        GetWorkPosition $getWorkPositionService,
        GetPostalCode $findPostalCodeService,
        GetRoleByName $getRoleByNameService,
        GetCompany $findCompanyService,
        UserFactory $factory,
        GetWorkPositionByCompany $getWorkPositionByCompany

    ) {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
        $this->getWorkPositionService = $getWorkPositionService;
        $this->findPostalCodeService = $findPostalCodeService;
        $this->getRoleByNameService = $getRoleByNameService;
        $this->findCompanyService = $findCompanyService;
        $this->factory = $factory;
        $this->getWorkPositionByCompany = $getWorkPositionByCompany;
    }

    public function __invoke(UserRegisterRequest $userRegisterRequest): User
    {
        $existingUser = $this->userRepository->findEmail($userRegisterRequest->emailAddress());

        if (null !== $existingUser) {
            throw new UserExistsException($userRegisterRequest->emailAddress());
        }

        $role = ($this->getRoleByNameService)(new GetRoleByNameRequest(Role::DEFAULT_COMPANY_ADMIN_ROLE));

        if (null === $role) {
            throw new InvalidRoleException();
        }

        $postalCode = ($this->findPostalCodeService)(
            new GetPostalCodeRequest($userRegisterRequest->company()->postalCodes()->value()));

        $workPosition = ($this->getWorkPositionByCompany)(
            new GetWorkPositionByCompanyRequest($userRegisterRequest->company()->id()->value()));

        $user = $this
            ->factory
            ->register(
                $userRegisterRequest->name(),
                'unknown',
                'unknown',
                0,
                0,
                'unknown',
                '',
                $userRegisterRequest->emailAddress(),
                $userRegisterRequest->password(),
                $workPosition,
                $workPosition->departments(),
                $workPosition->departments()->workPlace()->companies(),
                new DateTimeImmutable(),
                'unknown',
                0,
                'unknown',
                $postalCode,
            );

        $user->addRoles($role);
        
        $this->userRepository->save($user);

        $this->eventBus->publish(
            new UserRegisteredDomainEvent(
                $user->id(),
                $user->emailAddress(),
                new DateTimeImmutable()
            )
        );

        return $user;
    }
}
