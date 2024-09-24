<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\Department\Infrastructure\Controller\Api;


use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Department\Application\Api\DepartmentResponse;
use TFM\HolidaysManagement\Department\Application\Api\GetApiDepartmentsRequest;
use TFM\HolidaysManagement\Department\Application\Create\CreateDepartmentRequest;
use TFM\HolidaysManagement\Department\Application\Update\UpdateDepartmentRequest;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlaceRequest;

final class ApiDepartmentController extends AbstractController
{

    private CommandBus $commandBus;
    private TranslatorInterface $translator;

    public function __construct(CommandBus $commandBus, TranslatorInterface $translator)
    {
        $this->commandBus = $commandBus;
        $this->translator = $translator;
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("/"), name="api_get_departments", methods={"GET"})
     * @return JsonResponse
     */
    public function departments(): JsonResponse
    {
        $departments = $this->commandBus->handle(
            new GetApiDepartmentsRequest(
                []
            )
        );
        return new JsonResponse($departments);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("departments/status/{blocked}"), name="api_get_departments_by_status", methods={"GET"})
     * @param bool $blocked
     * @return JsonResponse
     */
    public function byStatus(bool $blocked): JsonResponse
    {
        $departments = $this->commandBus->handle(
            new GetApiDepartmentsRequest(
                [
                    'blocked' => $blocked
                ]
            )
        );
        return new JsonResponse($departments);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("department/company/{company}", name="api_get_department_by_company", methods={"GET"})
     * @param $company
     * @return JsonResponse
     */
    public function getByCompany($company): JsonResponse
    {
        $departments = $this->commandBus->handle(
            new GetApiDepartmentsRequest(
                [
                    'company' => $company,
                ]
            )
        );
        return new JsonResponse($departments);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("department/{id}", name="api_get_one_department", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $departments = $this->commandBus->handle(
            new GetApiDepartmentsRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($departments);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("department/workplace/{workPlace}", name="api_get_department_by_work_places", methods={"GET"})
     * @param $workPlace
     * @return JsonResponse
     */
    public function getByWorkPlace($workPlace): JsonResponse
    {
        $departments = $this->commandBus->handle(
            new GetApiDepartmentsRequest(
                [
                    'workPlace' => $workPlace,
                ]
            )
        );
        return new JsonResponse($departments);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_department", methods={"POST"})
     * @param $request
     * @return JsonResponse
     */
    public function set(Request $request): JsonResponse
    {

        try {
            $department = $this->commandBus->handle(new CreateDepartmentRequest(
                $request->get('name'),
                $request->get('description'),
                $request->get('phoneNumber'),
                intval($request->get('phoneExtension')),
                $request->get('workplace'),
                $request->get('blocked') === 'true' ? true : false
            ));
        } catch (\Exception $e) {
            throw new InvalidArgumentException(sprintf($this->translator->trans('company.controller.save.form.error'),
                $e->getMessage()));
        }

        return new JsonResponse(DepartmentResponse::fromDepartment($department));
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_update_department", methods={"PUT"})
     * @param $request
     * @return JsonResponse
     */
    public function update(string $id, Request $request): JsonResponse
    {

        $workplace = $this->commandBus->handle(new FindWorkPlaceRequest($request->get('workplace')));

        try {
            $department = $this->commandBus->handle(new UpdateDepartmentRequest(
                $id,
                $request->get('name'),
                $request->get('description'),
                $request->get('phoneNumber'),
                intval($request->get('phoneExtension')),
                $workplace,
                $request->get('blocked') === 'true' ? true : false,
            ));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf($this->translator->trans('update.department.error'),
                $e->getMessage()));
        }

        return new JsonResponse(DepartmentResponse::fromDepartment($department));
    }

}
