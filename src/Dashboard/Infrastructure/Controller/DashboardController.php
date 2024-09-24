<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Dashboard\Infrastructure\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TFM\HolidaysManagement\Request\Application\Calculate\CalculateRequestsByStatusRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetEventsCalendarRequest;
use TFM\HolidaysManagement\Request\Application\Get\GetRequestsRequest;
use TFM\HolidaysManagement\TypeRequest\Application\Search\GetAllTypesRequestsRequest;

final class DashboardController extends AbstractController
{
    private CommandBus $commandBus;
    private array $filters;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke()
    {

        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->init();

        $requests = $this->commandBus->handle(new GetRequestsRequest($this->filters));

        $events = $this->commandBus->handle(new GetEventsCalendarRequest($requests));

        $totalPendingRequests = $this->commandBus->handle(new CalculateRequestsByStatusRequest([
            'status'  => 'Pending', 'userId' => $this->getUser()->id()->value()
        ]));

        $totalAcceptedRequests = $this->commandBus->handle(new CalculateRequestsByStatusRequest([
            'status'  => 'Accepted', 'userId' => $this->getUser()->id()->value()
        ]));

        $typesRequest = $this->commandBus->handle(new GetAllTypesRequestsRequest());

        return $this->render('Dashboard/dashboard.html.twig', [
            'requests'              => $requests,
            'events'                => $events,
            'totalPendingRequests'  => $totalPendingRequests,
            'totalAcceptedRequests' => $totalAcceptedRequests,
            'type_requests'         => $typesRequest,
        ]);
    }

    private function init()
    {
        $user = $this->getUser();

        if (empty($this->getUser())) {
            return $this->render('partials/404_error.html.twig', []);
        }

        $this->filters = ['user' => $user];
        $this->filters += ['company' => $user->companies()->id()->value()];
    }
}
