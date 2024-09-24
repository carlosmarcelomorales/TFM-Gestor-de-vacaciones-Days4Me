<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetRequest
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRequestRequest $request): Request
    {
        return $this->repository->ofIdOrFail(new IdentUuid($request->id()));
    }
}
