<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Application\Search;

final class GetUsersCompanyRequest
{
    private string $companyId;

    public function __construct(string $companyId)
    {
        $this->companyId = $companyId;
    }

    public function companyId () : string
    {
        return $this->companyId;
    }
}
