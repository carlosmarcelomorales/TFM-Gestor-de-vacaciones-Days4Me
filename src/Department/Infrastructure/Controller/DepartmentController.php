<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Infrastructure\Controller;


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
use TFM\HolidaysManagement\Department\Application\Create\CreateDepartmentRequest;
use TFM\HolidaysManagement\Department\Application\Find\FindDepartmentRequest;
use TFM\HolidaysManagement\Department\Application\Get\GetDepartmentsRequest;
use TFM\HolidaysManagement\Department\Application\Update\UpdateDepartmentRequest;
use TFM\HolidaysManagement\Department\Domain\Exception\DepartmentIdNotExistsException;
use TFM\HolidaysManagement\Department\Domain\Exception\DepartmentNotBlockedException;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Department\Infrastructure\Form\CreateDepartmentType;


final class DepartmentController extends AbstractController
{
    private CommandBus $commandBus;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private array $filters;

    public function __construct(
        CommandBus $commandBus,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(): Response
    {
        $this->init();
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $departments = $this->commandBus->handle(new GetDepartmentsRequest($this->filters));
        return $this->render('Department/index.html.twig', ['departments' => $departments]);
    }

    public function addDepartment(): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->departmentForm(null, 'save_department');
        return $this->render('Department/department.html.twig', ['departmentForm' => $form->createView()]);
    }

    public function saveNewDepartment(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $form = $this->departmentForm(null, 'save_department');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(new CreateDepartmentRequest(
                    $form->getData()['name'],
                    $form->getData()['description'],
                    $form->getData()['phoneNumber'],
                    intval($form->getData()['phoneExtension']),
                    $form->getData()['workplace']->id()->value(),
                    $form->getData()['blocked'],
                ));
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (Exception $exception) {
                throw new InvalidArgumentException(
                    sprintf($this->translator->trans('department.controller.save.form.error'), $exception->getMessage())
                );
            }
        }

        return $this->render('Department/department.html.twig', ['departmentForm' => $form->createView()]);
    }

    private function departmentForm(Department $department = null, string $route): FormInterface
    {
        return $this->createForm(CreateDepartmentType::class, $department,
            [
                'method' => 'POST',
                'action' => $this->generateUrl($route)
            ]);
    }

    public function editDepartment(string $departmentId): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        try {
            $department = $this->commandBus->handle(
                new FindDepartmentRequest($departmentId)
            );

        } catch (Exception $exception) {
            throw new DepartmentIdNotExistsException($departmentId);
        }
        $form = $this->departmentForm($department, 'update_department');

        return $this->render(
            'Department/department.html.twig',
            [
                'departmentForm' => $form->createView(),
                'department' => $department
            ]);
    }

    public function updateDepartment(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }


        $form = $this->departmentForm(null, 'update_department');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle(
                    new UpdateDepartmentRequest(
                        $form->getData()['id'],
                        $form->getData()['name'],
                        $form->getData()['description'],
                        $form->getData()['phoneNumber'],
                        intval($form->getData()['phoneExtension']),
                        $form->getData()['workplace'],
                        $form->getData()['blocked'] ? $form->getData(
                        )['blocked'] : false,
                    )
                );
                $this->get('session')->getFlashBag()->add('ok',$this->translator->trans('messages.save.ok'));

            } catch (DepartmentIdNotExistsException $exception) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());

            } catch (DepartmentNotBlockedException $exception) {
                $this->get('session')->getFlashBag()->add('warning', $exception->getMessage());

            } catch (Exception $exception) {
                throw new InvalidArgumentException(sprintf($this->translator->trans('department.controller.update.form.error'),
                    $exception->getMessage()));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('list_department'));
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

    }

}
