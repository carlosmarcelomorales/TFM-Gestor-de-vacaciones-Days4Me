<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Create;

use DateTimeImmutable;
use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Application\Search\GetUser;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlace;
use TFM\HolidaysManagement\WorkPlace\Application\Find\FindWorkPlaceRequest;


final class CreateDepartment
{
    private DepartmentRepository $repository;
    private FindWorkPlace $findWorkPlace;
    private GetUser $getUser;

    public function __construct(
        DepartmentRepository $addRepository,
        FindWorkPlace $findWorkPlace,
        GetUser $getUser
    ) {
        $this->repository = $addRepository;
        $this->findWorkPlace = $findWorkPlace;
        $this->getUser = $getUser;
    }

    public function __invoke(CreateDepartmentRequest $departmentRequest): Department
    {
        $workPlace = ($this->findWorkPlace)(new FindWorkPlaceRequest($departmentRequest->workPlace()));

        $department = Department::create(
            new IdentUuid(),
            $workPlace,
            $departmentRequest->name(),
            $departmentRequest->description(),
            $departmentRequest->phone(),
            $departmentRequest->ext(),
            $departmentRequest->blocked(),
            new DateTimeImmutable(),
            null
        );
        $this->repository->save($department);
        return $department;
    }
}
