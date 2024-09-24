<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Get;


use TFM\HolidaysManagement\Request\Domain\RequestRepository;

final class GetRequestsByMultipleUsers
{

    private RequestRepository $repository;


    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke(GetRequestsByMultipleUsersRequest $getRequestsByMultipleUsersRequest) : array
    {

        $requests = [];
        $requestsByUser = [];

        foreach ($getRequestsByMultipleUsersRequest->users() as $user) {
            $requestsByUser[] = $this->repository->getAllByUser($user->id());
        }


        foreach ($requestsByUser as $requestUser) {
            foreach ($requestUser as $request) {
                if (!empty($request)) {
                    $requests[] = $request;
                }
            }
        }
        return $requests;
    }
}
