<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Security;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;
use TFM\HolidaysManagement\User\Infrastructure\Framework\Form\RequestTokenType;

class RequestTokenController extends AbstractController
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private CommandBus $commandBus;
    private TranslatorInterface  $translator;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        CommandBus $commandBus,
        TranslatorInterface $translator
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    public function __invoke(): Response
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/RequestToken/request-token.html.twig', [
            'form' => $this->getRequestTokenForm()->createView(),
        ]);
    }

    public function request(): Response
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('dashboard');
        }

        $request = $this->get('request_stack')->getCurrentRequest();

        $form = $this->getRequestTokenForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $result = $this->commandBus->handle($form->getData());

                if ($result) {
                    return $this->render('User/RequestToken/request-token-sent.html.twig');
                }

            } catch (UserNotExistsException $e) {
                $form->get('email')->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('User/RequestToken/request-token.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function getRequestTokenForm(): FormInterface
    {
        return $this->createForm(
            RequestTokenType::class,
            null,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('ui_request_token_request'),
            ]
        );
    }
}
