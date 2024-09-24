<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

use DateTimeImmutable;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\User\Domain\Model\Event\RequestTokenDomainEvent;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class RequestToken
{
    private UserRepository $userRepository;
    private DomainEventBus $eventBus;
    private TranslatorInterface  $translator;

    public function __construct(
        UserRepository $userRepository,
        DomainEventBus $eventBus,
        TranslatorInterface $translator
    ) {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
        $this->translator = $translator;
    }

    public function __invoke(RequestTokenRequest $request)
    {
        $user = $this->userRepository->findEmail($request->email());

        if (null === $user or $user->isBlocked()) {
            throw new UserNotExistsException(
                $this->translator->trans('user_not exist', ['email' => $request->email()]));
        }

        $token = $user->generateRecoveryToken();

        $this->userRepository->save($user);

        $this->processEvent($user->emailAddress(), $token, $request->route());
    }

    protected function processEvent(string $emailUser, string $token, ?string $route)
    {
        $this->eventBus->publish(
            new RequestTokenDomainEvent(
                $emailUser,
                $token,
                $route,
                new DateTimeImmutable()
            ));
    }
}
