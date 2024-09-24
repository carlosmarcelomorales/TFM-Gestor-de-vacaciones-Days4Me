<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Application\Create;


use DateTimeImmutable;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class CreateWorkPlace
{
    private WorkPlaceRepository $repository;
    private GetPostalCode $findPostalCodeService;

    public function __construct(WorkPlaceRepository $repository, GetPostalCode $findPostalCodeService)
    {
        $this->repository = $repository;
        $this->findPostalCodeService = $findPostalCodeService;
    }

    public function __invoke(CreateWorkPlaceRequest $workPlaceRequest): WorkPlace
    {
        $postalCode = ($this->findPostalCodeService)(new GetPostalCodeRequest($workPlaceRequest->postalCode()));

        $workPlace = WorkPlace::create(
            new IdentUuid(),
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
            new DateTimeImmutable(),
            $workPlaceRequest->company(),
            $postalCode,
            new DateTimeImmutable(),
            null
        );

        $this->repository->save($workPlace);
        return $workPlace;
    }
}