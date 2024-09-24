<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;
use TFM\HolidaysManagement\TypeRequest\Domain\TypeRequestRepository;

final class GetTypeRequest
{
    private TypeRequestRepository $repository;

    public function __construct(TypeRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetTypeRequestRequest $request): TypeRequest
    {
        return $this->repository->ofIdOrFail(new IdentUuid($request->id()));
    }
}