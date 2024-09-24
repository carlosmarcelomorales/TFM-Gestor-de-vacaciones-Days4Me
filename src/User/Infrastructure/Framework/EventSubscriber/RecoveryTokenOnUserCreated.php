<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\EventSubscriber;


use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\User\Application\Security\RequestToken;
use TFM\HolidaysManagement\User\Application\Security\RequestTokenRequest;
use TFM\HolidaysManagement\User\Domain\Model\Event\UserCreatedDomainEvent;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;

final class RecoveryTokenOnUserCreated implements MessageHandlerInterface
{
    private RequestToken $requestToken;

    public function __construct(RequestToken $requestToken)
    {
        $this->requestToken = $requestToken;
    }

    public function __invoke(UserCreatedDomainEvent $event)
    {
        try {
            ($this->requestToken)(new RequestTokenRequest(
                    $event->email(),
                    $event->aggregateId()->value(),
                    'Created'
                ));
        } catch (UserNotExistsException $e) {
        }
    }
}
