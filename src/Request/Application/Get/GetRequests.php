<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;

use TFM\HolidaysManagement\Request\Domain\RequestRepository;

final class GetRequests
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRequestsRequest $request): array
    {
        return $this->repository->allFiltered(
            $request->filters()
        );

    }
}
