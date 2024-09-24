<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Exception\TokenNotExistException;
use TFM\HolidaysManagement\User\Domain\Model\Factory\UserFactory;
use TFM\HolidaysManagement\User\Domain\UserRepository;

class UpdatePassword
{
    private UserRepository $userRepository;
    private UserFactory   $factory;
    private UserPasswordEncoderInterface $passwordEncoder;
    private TranslatorInterface $translator;

    public function __construct(
        UserRepository $userRepository,
        UserFactory $factory,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator
    ) {
        $this->userRepository = $userRepository;
        $this->factory = $factory;
        $this->passwordEncoder = $passwordEncoder;
        $this->translator = $translator;
    }

    public function __invoke(UpdatePasswordRequest $request)
    {
        $user = $this->userRepository->ofIdOrFail(new IdentUuid($request->id()));

        if (null === $user || $user->isBlocked()) {
            throw new TokenNotExistException($this->translator->trans('user.not.exists'));
        }

        $user->setPassword($request->password());
        $encodedPassword = $this->factory->symfonyFactoryEncodePassword($user);

        $user->setPassword($encodedPassword);

        if (null !== $request->email()) {
            $user->setEmailAddress($request->email());
        }

        $this->userRepository->save($user);

        return $user;
    }
}
