<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Infrastructure\Framework\EventSubscriber;


use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Mail\Application\SendNewRequestEmail;
use TFM\HolidaysManagement\User\Domain\Model\Event\UpdatedUserAvailableDaysDomainEvent;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class SendMailNewRequestOnAvailableDays implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private SendNewRequestEmail $sendNewRequestEmail;

    public function __construct(UserRepository $repository, SendNewRequestEmail $sendNewRequestEmail)
    {
        $this->userRepository = $repository;
        $this->sendNewRequestEmail = $sendNewRequestEmail;
    }

    public function __invoke(UpdatedUserAvailableDaysDomainEvent $event): void
    {
        $arrayResult [] = $this->userRepository->allFilters(
            [
                'user' => $event->aggregateId(),
                'headDepartment' => false,
                'department' => $event->departments()->id()->value()
            ]
        );

        $emails = [];
        foreach ($arrayResult as $object) {
            foreach ($object as $email) {
                $emails[] = $email->emailAddress();
            }
        }

        ($this->sendNewRequestEmail)($emails, $event->emailUser(),$event->availableDays(), $event->accumulatedDays(), $event->status());
    }
}
