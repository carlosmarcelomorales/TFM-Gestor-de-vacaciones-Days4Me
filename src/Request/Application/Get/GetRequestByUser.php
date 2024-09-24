<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class GetRequestByUser
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetRequestByUserRequest $request): array
    {
        return $this->repository->getAllByUser(new IdentUuid($request->id()));
    }
}