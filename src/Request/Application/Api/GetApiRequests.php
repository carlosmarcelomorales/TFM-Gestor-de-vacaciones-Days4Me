<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Api;

use TFM\HolidaysManagement\Request\Domain\RequestRepository;

final class GetApiRequests
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiRequestsRequest $request): array
    {
        $requests = $this->repository->allFiltered($request->filters());
        return RequestResponse::fromArray($requests);
    }
}