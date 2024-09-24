<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Controller;


use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserExistsException;
use TFM\HolidaysManagement\User\Infrastructure\Framework\Form\RestoreEmailAndPasswordType;

final class ProfileController extends AbstractController
{
    private CommandBus $commandBus;
    private RequestStack $requestStack;
    private TranslatorInterface $translator;

    public function __construct(CommandBus $commandBus, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function __invoke(): Response
    {
        $user = $this->getUser();
        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        return $this->render('User/profile.html.twig', [
            'user' => $user,
            'form' => $this->profileForm($user, 'save_profile')->createView()
        ]);
    }

    private function profileForm($user, string $route): FormInterface
    {
        return $this->createForm(
            RestoreEmailAndPasswordType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]
        );
    }

    public function save(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }
        $user = $this->getUser();
        $request = $this->requestStack->getCurrentRequest();

        $form = $this->profileForm($this->getUser(), 'save_profile');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->commandBus->handle($form->getData());
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

                return $this->redirectToRoute('profile');
            } catch (UserExistsException $e) {
                $form->get('plain_password')->addError(new FormError($e->getMessage()));
            }
        }
        return $this->render('User/profile.html.twig', [
            'user' => $user,
            'form' => $this->profileForm($user, 'save_profile')->createView()
        ]);

    }
}

