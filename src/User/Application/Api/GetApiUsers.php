<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Api;

use TFM\HolidaysManagement\StatusRequest\Domain\StatusRequestRepository;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class GetApiUsers
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetApiUsersRequest $request): array
    {
        $users = $this->repository->allFiltered($request->filters());
        return UserResponse::fromArray($users);
    }
}