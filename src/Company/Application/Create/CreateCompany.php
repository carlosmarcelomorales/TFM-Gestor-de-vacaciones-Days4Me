<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Application\Create;

use DateTimeImmutable;
use TFM\HolidaysManagement\Company\Domain\CompanyRepository;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyAlreadyExistsException;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Company\Domain\Model\Event\CompanySavedDomainEvent;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCode;
use TFM\HolidaysManagement\Country\Application\PostalCode\GetPostalCodeRequest;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class CreateCompany
{
    private CompanyRepository $companyRepository;
    private DomainEventBus $eventBus;

    private GetPostalCode $findPostalCodeService;

    public function __construct(
        CompanyRepository $companyRepository,
        DomainEventBus $eventBus,
        GetPostalCode $findPostalCodeService
    ) {
        $this->companyRepository = $companyRepository;
        $this->eventBus = $eventBus;
        $this->findPostalCodeService = $findPostalCodeService;
    }

    public function __invoke(CreateCompanyRequest $createCompanyRequest): Company
    {
        $company = $this->companyRepository->ofVat($createCompanyRequest->vat());

        if (null !== $company) {
            throw new CompanyAlreadyExistsException(
                $createCompanyRequest->vat()
            );
        }

        $postalCode = ($this->findPostalCodeService)(new GetPostalCodeRequest($createCompanyRequest->postalCode()));

        $company = new Company(
            new IdentUuid(),
            $createCompanyRequest->vat(),
            $createCompanyRequest->name(),
            $createCompanyRequest->description(),
            $createCompanyRequest->webSite(),
            $createCompanyRequest->phoneNumber1(),
            $createCompanyRequest->phoneNumber2(),
            $createCompanyRequest->email(),
            $createCompanyRequest->streetName(),
            $createCompanyRequest->number(),
            $createCompanyRequest->floor(),
            $postalCode,
            $createCompanyRequest->isBlocked(),
            $createCompanyRequest->businessDays(),
            null,
            null,
            null,

        );
        $this->companyRepository->save($company);

        $this->eventBus->publish(
            new CompanySavedDomainEvent(
                $company->id(),
                $company->email(),
                new DateTimeImmutable()
            )
        );

        return $company;
    }
}
