<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class GetUser
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetUserRequest $request): User
    {
        return $this->repository->ofIdOrFail(new IdentUuid($request->id()));
    }
}
