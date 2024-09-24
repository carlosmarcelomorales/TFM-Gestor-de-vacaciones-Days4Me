<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Api;

use TFM\HolidaysManagement\TypeRequest\Domain\TypeRequestRepository;

final class GetApiTypesRequest
{
    private TypeRequestRepository $repository;

    public function __construct(TypeRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiTypesRequestRequest $request): array
    {
        $typeRequests = $this->repository->allFiltered($request->filters());
        return TypeRequestResponse::fromArray($typeRequests);
    }
}