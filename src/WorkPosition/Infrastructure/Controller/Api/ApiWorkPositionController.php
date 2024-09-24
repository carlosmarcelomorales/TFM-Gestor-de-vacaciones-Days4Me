<?php
declare(strict_types=1);


namespace TFM\HolidaysManagement\WorkPosition\Infrastructure\Controller\Api;

use League\Tactician\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Department\Application\Find\FindDepartmentRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Api\GetApiWorkPositionsRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Api\WorkPositionResponse;
use TFM\HolidaysManagement\WorkPosition\Application\Create\CreateWorkPositionRequest;
use TFM\HolidaysManagement\WorkPosition\Application\Update\UpdateWorkPositionRequest;

final class ApiWorkPositionController extends AbstractController
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
     * @Route("/"), name="api_get_status_work_position", methods={"GET"})
     * @return JsonResponse
     */

    public function workPositions(): JsonResponse
    {
        $work_positions = $this->commandBus->handle(
            new GetApiWorkPositionsRequest(
                []
            )
        );
        return new JsonResponse($work_positions);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("company/{company}"), name="api_get_status_work_position_by_company", methods={"GET"})
     * @param string $company
     * @return JsonResponse
     */

    public function byCompany(string $company): JsonResponse
    {
        $work_positions = $this->commandBus->handle(
            new GetApiWorkPositionsRequest(
                [
                    'name' => $company
                ]
            )
        );
        return new JsonResponse($work_positions);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("{id}", name="api_get_one_work_position", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function ById(string $id): JsonResponse
    {
        $work_positions = $this->commandBus->handle(
            new GetApiWorkPositionsRequest(
                [
                    'id' => $id,
                ]
            )
        );
        return new JsonResponse($work_positions);
    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("is_head_department/{isHeadDepartment}", name="api_get_one_work_position_is_head_department", methods={"GET"})
     * @param string $isHeadDepartment
     * @return JsonResponse
     */
    public function ByHeadDepartment(string $isHeadDepartment): JsonResponse
    {
        $work_positions = $this->commandBus->handle(
            new GetApiWorkPositionsRequest(
                [
                    'headDepartment' => $isHeadDepartment,
                ]
            )
        );
        return new JsonResponse($work_positions);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("set", name="api_set_department", methods={"POST"})
     * @param $request
     * @return JsonResponse
     */
    public function set(Request $request): JsonResponse
    {

        $departments = $this->commandBus->handle(new FindDepartmentRequest($request->get('departments')));

        try {
            $workPosition = $this->commandBus->handle(
                new CreateWorkPositionRequest(
                    $request->get('name'),
                    $request->get('headDepartment') === 'true' ? true : false,
                    $departments
                )
            );
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf($this->translator->trans('workPosition.controller.save.form.error'),
                $e->getMessage()));
        }

        return new JsonResponse(WorkPositionResponse::fromWorkPosition($workPosition));

    }

    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')")
     * @Route("update/{id}", name="api_update_department", methods={"PUT"})
     * @param $request
     * @return JsonResponse
     */
    public function update(string $id, Request $request): JsonResponse
    {

        $departments = $this->commandBus->handle(new FindDepartmentRequest($request->get('departments')));

        $workPosition = $this->commandBus->handle(new UpdateWorkPositionRequest(
            $id,
            $request->get('name'),
            $request->get('headDepartment') === 'true' ? true : false,
            $departments
        ));

        return new JsonResponse(WorkPositionResponse::fromWorkPosition($workPosition));

    }

}
