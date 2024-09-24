<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Framework\EventSubscriber;

use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Mail\Application\SendNewRequestEmail;
use TFM\HolidaysManagement\Request\Domain\Model\Event\UpdateStatusRequestDomainEvent;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequest;
use TFM\HolidaysManagement\StatusRequest\Application\Search\GetStatusRequestRequest;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserAvailableDays;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserUpdateAvailableDaysException;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class SendEmailOnRequestStatusChanged implements MessageHandlerInterface
{
    private UserRepository $userRepository;
    private UpdateUserAvailableDays $availableDays;
    private SendNewRequestEmail $sendNewRequestEmail;
    private GetStatusRequest $getStatusRequestService;

    public function __construct(
        UserRepository $userRepository,
        UpdateUserAvailableDays $availableDays,
        SendNewRequestEmail $sendNewRequestEmail,
        GetStatusRequest $getStatusRequestService
    ) {
        $this->userRepository = $userRepository;
        $this->availableDays = $availableDays;
        $this->sendNewRequestEmail = $sendNewRequestEmail;
        $this->getStatusRequestService = $getStatusRequestService;
    }

    public function __invoke(UpdateStatusRequestDomainEvent $event): void
    {
        try {
            $user = $this->userRepository->ofIdOrFail(new IdentUuid($event->userId()));
            $statusRequest = ($this->getStatusRequestService)(new GetStatusRequestRequest($event->status()));

            ($this->sendNewRequestEmail)([$user->emailAddress()], $user->emailAddress(), $user->availableDays(),
                $user->accumulatedDays(), $statusRequest->name());
        } catch (Exception $e) {
            throw new UserUpdateAvailableDaysException($user->emailAddress());
        }
    }
}
