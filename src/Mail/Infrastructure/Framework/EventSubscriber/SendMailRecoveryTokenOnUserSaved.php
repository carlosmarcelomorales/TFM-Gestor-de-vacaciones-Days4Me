<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Mail\Infrastructure\Framework\EventSubscriber;


use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Mail\Application\SendRecoveryPasswordEmail;
use TFM\HolidaysManagement\User\Domain\Model\Event\RequestTokenDomainEvent;

final class SendMailRecoveryTokenOnUserSaved implements MessageHandlerInterface
{
    private SendRecoveryPasswordEmail $sendRecoveryEmail;

    public function __construct(SendRecoveryPasswordEmail $sendRecoveryEmail)
    {
        $this->sendRecoveryEmail = $sendRecoveryEmail;
    }

    public function __invoke(RequestTokenDomainEvent $event): void
    {
        ($this->sendRecoveryEmail)($event->emailUser(), $event->token(), $event->route());
    }
}
