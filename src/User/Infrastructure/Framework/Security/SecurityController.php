<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Framework\Security;

use Exception;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use TFM\HolidaysManagement\Company\Application\Create\CreateCompanyRequest;
use TFM\HolidaysManagement\User\Application\Register\UserRegister;
use TFM\HolidaysManagement\User\Application\Register\UserRegisterRequest;
use TFM\HolidaysManagement\User\Infrastructure\Framework\Form\UserType;


class SecurityController extends AbstractController
{
    private UserRegister $userRegister;
    private RouterInterface $router;
    private CommandBus $commandBus;

    public function __construct(
        UserRegister $userRegister,
        RouterInterface $router,
        CommandBus $commandBus
    ) {
        $this->userRegister = $userRegister;
        $this->router = $router;
        $this->commandBus = $commandBus;
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        if ($user) {
            if (false === $user->companies()->isUpdated() && in_array('ROLE_COMPANY_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('edit_company', ['companyId' => $user->companies()->id()->value()]);
            }
            return $this->redirectToRoute('dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout(UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        return new RedirectResponse($urlGenerator->generate('ui_login'));

    }

    public function register()
    {
        $form = $this->getCreateUserForm();

        return $this->render('Register/register.html.twig',
            ['form' => $form->createView()]);
    }

    private function getCreateUserForm(): FormInterface
    {
        return $this->createForm(UserType::class, null, [
            'action' => $this->generateUrl('ui_create')
        ]);
    }

    public function create(
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $formAuthenticator
    ) {
        $form = $this->getCreateUserForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            try {
                $company = $this->commandBus->handle(new CreateCompanyRequest(
                    $form->getData()['company_vat'],
                    $form->getData()['company_name'],
                    '',
                    '',
                    $form->getData()['phone_number'],
                    '',
                    $form->getData()['email'],
                    '',
                    0,
                    '',
                    $form->getData()['postal_code'],
                    false,
                    false));

                $user = $this->commandBus->handle(
                    new UserRegisterRequest(
                        $form->getData()['email'],
                        $form->getData()['name'],
                        $form->getData()['password'],
                        $company
                    )
                );
                $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $formAuthenticator,
                    'main'
                );
                return new RedirectResponse($this->router->generate('edit_company',
                    ['companyId' => $company->id()->value()]));

            } catch (Exception $e) {
                $form->get('name')->addError(new FormError($e->getMessage()));
                return $this->render('Register/register.html.twig',
                    ['form' => $form->createView()]);
            }
        }
        //return new RedirectResponse($this->router->generate('ui_register'));
    }
}
