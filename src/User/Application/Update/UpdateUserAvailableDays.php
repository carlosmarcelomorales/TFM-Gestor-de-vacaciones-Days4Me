<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Update;


use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Event\UpdatedUserAvailableDaysDomainEvent;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class UpdateUserAvailableDays
{
    private UserRepository $userRepository;
    private DomainEventBus $eventBus;

    public function __construct(UserRepository $userRepository, DomainEventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UpdateUserAvailableDaysRequest $availableDaysRequest)
    {
        $user = $this->userRepository->ofIdOrFail(new IdentUuid($availableDaysRequest->id()));

        $user->setAvailableDays($availableDaysRequest->availableDays());
        $user->setAccumulatedDays($availableDaysRequest->accumulatedDays());

        $this->userRepository->save($user);

        $this->processEvent($user, $availableDaysRequest);
    }

    protected function processEvent(User $user, UpdateUserAvailableDaysRequest $availableDaysRequest): void
    {
        $this->eventBus->publish(
            new UpdatedUserAvailableDaysDomainEvent(
                $user->id(),
                $user->emailAddress(),
                $user->departments(),
                $availableDaysRequest->availableDays(),
                $availableDaysRequest->accumulatedDays(),
                $availableDaysRequest->status(),
                new DateTimeImmutable()
            ));
    }
}
