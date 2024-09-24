<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;

use TFM\HolidaysManagement\Request\Domain\RequestRepository;

final class GetRequestsByRange
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRequestsByRangeRequest $request): array
    {
        return $this->repository->rangeRequestDays($request->filters());
    }
}
