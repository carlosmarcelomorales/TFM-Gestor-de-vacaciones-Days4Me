<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Controller;

use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Application\Create\CreateUserRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUserRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUsersRequest;
use TFM\HolidaysManagement\User\Application\Update\UpdateUserRequest;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;
use TFM\HolidaysManagement\User\Infrastructure\Framework\Form\CreateUserType;


final class UserController extends AbstractController
{
    private CommandBus $commandBus;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private array $filters;

    public function __construct(
        CommandBus $commandBus,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }


    public function __invoke(): Response
    {
        $this->init();
        $users = $this->commandBus->handle(new GetUsersRequest($this->filters));
        return $this->render('User/index.html.twig', ['users' => $users]);
    }


    private function createUserForm(User $user = null, string $route): FormInterface
    {

        return $this->createForm(
            CreateUserType::class,
            $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route),
            ]
        );
    }

    public function add(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->createUserForm(null, 'save_user');
        return $this->render('User/Create/create_user.html.twig', ['Form' => $form->createView()]);
    }


    public function save(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->createUserForm(null, 'save_user');

        $postalCode = $request->get('postal_code') ? $request->get('postal_code') : PostalCode::DEFAULT_NOT_POSTAL_CODE;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new CreateUserRequest(
                    $form->getData()['name'],
                    $form->getData()['lastName'],
                    $form->getData()['dni'],
                    $form->getData()['availableDays'],
                    $form->getData()['accumulatedDays'],
                    $form->getData()['socialSecurityNumber'],
                    $form->getData()['phoneNumber'],
                    $form->getData()['emailAddress'],
                    IdentUuid::generate()->value(),
                    $form->getData()['roles'],
                    $form->getData()['workPositions']->id()->value(),
                    $form->getData()['incorporationDate'],
                    $form->getData()['streetName'],
                    $form->getData()['number'],
                    $form->getData()['floor'],
                    $postalCode,
                    $this->getUser()->companies()->id()->value()
                ));

                $this->get('session')->getFlashBag()->add('ok', $this->translator->trans('messages.save.ok'));
            } catch (Exception $e) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('user.controller.save.user'), $e->getMessage()));
            }
        }
        return $this->render('User/Create/create_user.html.twig', ['Form' => $form->createView()]);
    }

    public function edit(string $userId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $user = $this->commandBus->handle(new GetUserRequest(
                $userId
            ));
        } catch (Exception $e) {
            throw new UserNotExistsException($userId);
        }

        $form = $this->createUserForm($user, 'update_user');

        $positionInfo = [
            'postalCode' => $user->postalCodes()->value(),
            'town' => $user->postalCodes()->towns()[0]->name(),
            'region' => $user->postalCodes()->towns()[0]->regions()->name(),
            'country' => $user->postalCodes()->towns()[0]->regions()->countries()->name()
        ];

        return $this->render('User/Create/create_user.html.twig', [
            'Form' => $form->createView(),
            'user' => $user,
            'positionInfo' => $positionInfo
        ]);
    }

    public function update(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->createUserForm(null, 'update_user');

        $form->handleRequest($request);

        $postalCode = $request->get('postal_code') ?? PostalCode::DEFAULT_NOT_POSTAL_CODE;

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $this->commandBus->handle(new UpdateUserRequest(
                        $form->getData()['id'],
                        $form->getData()['name'],
                        $form->getData()['lastName'],
                        $form->getData()['dni'],
                        $form->getData()['availableDays'],
                        $form->getData()['accumulatedDays'],
                        $form->getData()['socialSecurityNumber'],
                        $form->getData()['phoneNumber'],
                        $form->getData()['emailAddress'],
                        $form->getData()['roles'],
                        $form->getData()['workPositions']->id()->value(),
                        $form->getData()['incorporationDate'],
                        $form->getData()['streetName'],
                        $form->getData()['number'],
                        $form->getData()['floor'],
                        $form->getData()['blocked'] ? $form->getData()['blocked'] : false,
                        $postalCode,
                        $this->getUser()->companies()->id()->value()
                    )
                );
                $this->get('session')->getFlashBag()->add('ok', $this->translator->trans('messages.save.ok'));

            } catch (Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', 'll');
                throw new InvalidArgumentException(sprintf($this->translator->trans('user.controller.update.form.error'), $e->getMessage()));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('list_user'));
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        if ($this->isGranted('ROLE_USER')) {
            $this->filters += ['user' => $user->id()->value()];
        }

        if ($this->isGranted('ROLE_COMPANY_HEAD')) {
            $this->filters += ['department' => $user->departments()->id()->value()];
        }
    }

}
