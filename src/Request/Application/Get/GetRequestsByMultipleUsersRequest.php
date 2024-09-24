<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


final class GetRequestsByMultipleUsersRequest
{

    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function users() : array
    {
        return $this->users;
    }

}
