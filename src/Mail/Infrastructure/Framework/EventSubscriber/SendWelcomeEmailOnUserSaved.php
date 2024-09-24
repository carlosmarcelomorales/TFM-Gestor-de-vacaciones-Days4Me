<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Infrastructure\Framework\EventSubscriber;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Mail\Domain\Model\Service\SendEmailService;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\User\Domain\Model\Event\UserRegisteredDomainEvent;

final class SendWelcomeEmailOnUserSaved implements MessageHandlerInterface
{

    private SendEmailService $sendWelcomeEmailService;

    private DomainEventBus $domainEventBus;

    public function __construct(SendEmailService $sendWelcomeEmailService, DomainEventBus $domainEventBus)
    {
        $this->sendWelcomeEmailService = $sendWelcomeEmailService;
        $this->domainEventBus = $domainEventBus;
    }

    public function __invoke(UserRegisteredDomainEvent $event): void
    {
        ($this->sendWelcomeEmailService)($event->email());
    }

}
