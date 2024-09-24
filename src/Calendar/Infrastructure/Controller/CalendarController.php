<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Calendar\Infrastructure\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TFM\HolidaysManagement\Department\Application\Get\GetDepartmentsRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetEventsCalendarRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestByUserRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestsByMultipleUsersRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestsRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetAllTypesRequestsRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUsersCompanyRequest;
use TFM\HolidaysManagement\User\Application\Search\GetUsersRequest;

final class CalendarController extends AbstractController
{
    private CommandBus $commandBus;
    private array $filters;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke()
    {
        $this->init();

        if ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_COMPANY_HEAD')) {
            $users = $this->commandBus->handle(new GetUsersRequest(['department' => $this->getUser()->departments()->id()->value()]));
        } else {
            $users = $this->commandBus->handle(new GetUsersCompanyRequest($this->getUser()->companies()->id()->value()));
        }

        $requests = $this->commandBus->handle(new GetRequestsByMultipleUsersRequest($users));

        $events = $this->commandBus->handle(new GetEventsCalendarRequest($requests));

        $typesRequest = $this->commandBus->handle(new GetAllTypesRequestsRequest());

        $departments = $this->commandBus->handle(new GetDepartmentsRequest($this->filters));

        return $this->render('Calendar/calendar.html.twig', [
            'events' => $events,
            'users' => $users,
            'type_requests' => $typesRequest,
            'departments' => $departments
        ]);
    }


    public function getRequestsByUser(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $userId = $request->get('user_id');

        $requests = $this->commandBus->handle(new GetRequestByUserRequest($userId));

        $events = $this->parseRequestsToEvents($requests);

        return new JsonResponse(['events' => $events]);

    }

    public function getRequestsByTypeRequest(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $typeRequest = $request->get('type_request_id');

        $requests = $this->commandBus->handle(new GetRequestsRequest(['typesRequest' => $typeRequest]));

        $events = $this->parseRequestsToEvents($requests);

        return new JsonResponse(['events' => $events]);
    }

    public function getRequestsByDepartment(Request $request): Response
    {
        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }
        $department = $request->get('department_id');

        $requests = $this->commandBus->handle(new GetRequestsRequest(['department' => $department]));

        $events = $this->parseRequestsToEvents($requests);

        return new JsonResponse(['events' => $events]);
    }

    private function parseRequestsToEvents(array $requests): string
    {
        return $this->commandBus->handle(new GetEventsCalendarRequest($requests));
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($user)) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['company' => $user->companies()->id()->value()];

        if ($this->isGranted('ROLE_USER') || $this->isGranted('ROLE_COMPANY_HEAD')) {
            $this->filters += ['id' => $user->departments()->id()->value()];
        }
    }


}
