<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Calculate;


use TFM\HolidaysManagement\Request\Domain\RequestRepository;

final class CalculateRequestsByStatus
{
    private RequestRepository $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function __invoke(CalculateRequestsByStatusRequest $calculateRequestsByStatusRequest)
    {

        return $this->requestRepository->countStatus($calculateRequestsByStatusRequest->filters());
    }
}
