<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Framework\EventSubscriber;


use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Request\Domain\Model\Event\CreateRequestDomainEvent;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDays;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDaysRequest;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserUpdateAvailableDaysException;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class UpdateUserOnRequestSaved implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private UpdateUserAvailableDays $availableDays;

    public function __construct(
        UserRepository $repository,
        UpdateUserAvailableDays $availableDays
    ) {
        $this->userRepository = $repository;
        $this->availableDays = $availableDays;
    }

    public function __invoke(CreateRequestDomainEvent $event): void
    {
        try {
            ($this->availableDays)(
                new UpdateUserAvailableDaysRequest(
                    $event->aggregateId(),
                    $event->emailUser(),
                    $event->availableDays(),
                    $event->accumulatedDays(),
                    $event->status()
                )
            );

        } catch (Exception $e) {
            throw new UserUpdateAvailableDaysException($event->emailUser());
        }
    }
}
