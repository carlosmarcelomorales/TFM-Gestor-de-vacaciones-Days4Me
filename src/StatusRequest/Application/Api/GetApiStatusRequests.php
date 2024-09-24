<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Application\Api;

use TFM\HolidaysManagement\StatusRequest\Domain\StatusRequestRepository;

final class GetApiStatusRequests
{
    private StatusRequestRepository $repository;

    public function __construct(StatusRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiStatusRequestsRequest $request): array
    {
        $statusRequests = $this->repository->allFiltered($request->filters());
        return StatusRequestResponse::fromArray($statusRequests);
    }
}