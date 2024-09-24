<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Update;


use DateTimeImmutable;
use Symfony\Contracts\Translation\TranslatorInterface;
use TFM\HolidaysManagement\Company\Application\Get\GetCompany;
use TFM\HolidaysManagement\Company\Application\Get\GetCompanyRequest;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Department\Application\Get\GetDepartments;
use TFM\HolidaysManagement\Department\Application\Get\GetDepartmentsRequest;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Exception\WorkPlaceIdNotExistsException;
use TFM\HolidaysManagement\WorkPlace\Domain\Exception\WorkPlaceNotBlockedException;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class UpdateWorkPlace
{
    private WorkPlaceRepository $repository;
    private GetPostalCode $findPostalCodeService;
    private GetCompany $findCompanyService;
    private GetDepartments $findDepartmentService;
    private TranslatorInterface $translation;

    public function __construct(WorkPlaceRepository $workPlaceRepository,  GetPostalCode $findPostalCodeService,  GetCompany $findCompanyService, GetDepartments $findDepartmentService, TranslatorInterface $translation)
    {
        $this->repository = $workPlaceRepository;
        $this->findPostalCodeService = $findPostalCodeService;
        $this->findCompanyService = $findCompanyService;
        $this->findDepartmentService = $findDepartmentService;
        $this->translation = $translation;
    }

    public function __invoke(UpdateWorkPlaceRequest $workPlaceRequest): WorkPlace
    {
        $workPlace = $this->repository->getWorkPlaceById(new IdentUuid($workPlaceRequest->id()));

        if ($workPlace === null) {
            throw new WorkPlaceIdNotExistsException($workPlaceRequest->id());
        }

        $countDepartments = ($this->findDepartmentService)(new GetDepartmentsRequest(['workPlace' => $workPlace->id()]));
        if (!empty($countDepartments) and $workPlaceRequest->blocked() === true) {
            throw new WorkPlaceNotBlockedException($this->translation->trans('workPlace.not.blocked'));
        }

        $postalCode = ($this->findPostalCodeService)(new GetPostalCodeRequest($workPlaceRequest->postalCode()));

        $company = ($this->findCompanyService)(new GetCompanyRequest($workPlaceRequest->company()));

        $workPlace->update(
            $workPlaceRequest->name(),
            $workPlaceRequest->description(),
            $workPlaceRequest->phoneNumber1(),
            $workPlaceRequest->phoneNumber2(),
            $workPlaceRequest->email(),
            $workPlaceRequest->permitAccumulate(),
            $workPlaceRequest->monthPermittedToAccumulate(),
            $workPlaceRequest->holidayStartYear(),
            $workPlaceRequest->holidayEndYear(),
            $workPlaceRequest->streetName(),
            $workPlaceRequest->number(),
            $workPlaceRequest->floor(),
            $workPlaceRequest->blocked(),
            $workPlaceRequest->blockedOn(),
            $company,
            $postalCode,
            new DateTimeImmutable()
        );

        $this->repository->save($workPlace);

        return $workPlace;
    }
}
