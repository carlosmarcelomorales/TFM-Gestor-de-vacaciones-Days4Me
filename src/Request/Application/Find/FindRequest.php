<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Application\Find;


use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class FindRequest
{
    private RequestRepository $repository;

    public function __construct(RequestRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindRequestRequest $requestRequest): Request
    {
        return $this->repository->getRequestById(new IdentUuid($requestRequest->id()));
    }
}
