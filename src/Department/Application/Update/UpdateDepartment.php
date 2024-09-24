<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Application\Update;


use DateTimeImmutable;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;
use TFM\HolidaysManagement\Department\Domain\Exception\DepartmentIdNotExistsException;
use TFM\HolidaysManagement\Department\Domain\Exception\DepartmentNotBlockedException;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Application\Search\GetUsers;
use TFM\HolidaysManagement\User\Application\Search\GetUsersRequest;

final class UpdateDepartment
{
    private DepartmentRepository $repository;
    private GetUsers $findUsersService;
    private TranslatorInterface $translation;

    public function __construct(DepartmentRepository $updateRepository, GetUsers $findUsersService,  TranslatorInterface $translation)
    {
        $this->repository = $updateRepository;
        $this->findUsersService = $findUsersService;
        $this->translation = $translation;
    }

    public function __invoke(UpdateDepartmentRequest $departmentRequest): Department
    {
        $department = $this->repository->getDepartmentById(new IdentUuid($departmentRequest->id()));

        if ($department === null) {
            throw new DepartmentIdNotExistsException($departmentRequest->id());
        }

        $countUsers = ($this->findUsersService)(new GetUsersRequest(['department' => $department->id()]));
        if (!empty($countUsers) and $departmentRequest->blocked() === true) {
            throw new DepartmentNotBlockedException($this->translation->trans('department.not.blocked'));
        }

        $department->update(
            $departmentRequest->workPlace(),
            $departmentRequest->name(),
            $departmentRequest->description(),
            $departmentRequest->phone(),
            $departmentRequest->ext(),
            $departmentRequest->blocked(),
            new DateTimeImmutable()
            );

        $this->repository->save($department);

        return $department;
    }
}
