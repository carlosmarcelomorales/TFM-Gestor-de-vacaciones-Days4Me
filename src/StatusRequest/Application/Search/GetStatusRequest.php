<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\StatusRequest\Domain\StatusRequestRepository;

final class GetStatusRequest
{
    private StatusRequestRepository $repository;

    public function __construct(StatusRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetStatusRequestRequest $request): StatusRequest
    {
        return $this->repository->ofIdOrFail(new IdentUuid($request->id()));
    }
}