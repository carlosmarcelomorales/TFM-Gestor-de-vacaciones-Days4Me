<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Search;

use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class GetUsersByCompany
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUsersCompanyRequest $getUsersCompanyRequest)
    {
        return $this->userRepository->getAllByCompanyId(new IdentUuid($getUsersCompanyRequest->companyId()));
    }
}
