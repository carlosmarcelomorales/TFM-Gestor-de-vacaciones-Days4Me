<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Search;

use TFM\HolidaysManagement\User\Domain\UserRepository;

final class GetUsers
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetUsersRequest $request): array
    {
        return $this->repository->allFilters($request->filters());
    }
}
