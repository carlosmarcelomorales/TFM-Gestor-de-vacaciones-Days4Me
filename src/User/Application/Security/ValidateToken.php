<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\User\Domain\Model\Exception\TokenNotExistException;
use TFM\HolidaysManagement\User\Domain\UserRepository;

class ValidateToken
{
    private UserRepository $userRepository;
    private TranslatorInterface  $translator;

    public function __construct(
        UserRepository $userRepository,
        TranslatorInterface $translator
    ) {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function __invoke(ValidateTokenRequest $request)
    {
        $recoveryToken = substr($request->token(), 0, 16);
        $validationToken = substr($request->token(), 16);

        $user = $this->userRepository->ofTokenOrFail($recoveryToken);

        if (null === $user || !$user->isTokenValid($validationToken)) {
            throw new TokenNotExistException($this->translator->trans('user.not.exists'));
        }

        return $user;
    }
}
