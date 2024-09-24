<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Security;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use TFM\HolidaysManagement\User\Application\Security\ValidateTokenRequest;
use TFM\HolidaysManagement\User\Domain\Model\Exception\TokenNotExistException;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;
use TFM\HolidaysManagement\User\Infrastructure\Framework\Form\RestorePasswordType;

class RestorePasswordController extends AbstractController
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private CommandBus $commandBus;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, CommandBus $commandBus)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $token): Response
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('ui_user_profile_edit');
        }

        try {
            $user = $this->commandBus->handle(new ValidateTokenRequest($token));
        } catch (TokenNotExistException $e) {
            return $this->render('User/RestorePassword/token-invalid.html.twig');
        }

        return $this->render('User/RestorePassword/restore-password.html.twig', [
            'form' => $this->getPasswordForm($user, $token)->createView(),
        ]);
    }

    public function request(string $token): Response
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('ui_user_profile_edit');
        }

        try {
            $user = $this->commandBus->handle(new ValidateTokenRequest($token));
        } catch (TokenNotExistException $e) {
            return $this->render('User/RestorePassword/token-invalid.html.twig');
        }

        $request = $this->get('request_stack')->getCurrentRequest();

        $form = $this->getPasswordForm($user, $token);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {

                $user = $this->commandBus->handle($form->getData());

                return $this->redirectToRoute('ui_login');
            } catch (UserNotExistsException $e) {
                $form->get('plain_password')->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('User/RestorePassword/restore-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getPasswordForm($user, string $token): FormInterface
    {
        return $this->createForm(
            RestorePasswordType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('ui_restore_password_request', [
                    'token' => $token,
                ]),
            ]
        );
    }
}
