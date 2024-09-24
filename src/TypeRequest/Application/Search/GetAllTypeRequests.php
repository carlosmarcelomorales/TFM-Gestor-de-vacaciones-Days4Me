<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Search;

use TFM\HolidaysManagement\TypeRequest\Domain\TypeRequestRepository;

final class GetAllTypeRequests
{

    private TypeRequestRepository $repository;

    public function __construct(TypeRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetAllTypesRequestsRequest $getAllTypesRequestsRequest)
    {
        return $this->repository->getAll();
    }
}
