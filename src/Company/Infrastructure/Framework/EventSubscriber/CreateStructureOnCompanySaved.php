<?php

declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Infrastructure\Framework\EventSubscriber;

use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TFM\HolidaysManagement\Company\Application\Get\GetCompany;
use TFM\HolidaysManagement\Company\Application\Get\GetCompanyRequest;
use TFM\HolidaysManagement\Company\Domain\Model\Event\CompanySavedDomainEvent;
use TFM\HolidaysManagement\Department\Application\Create\CreateDepartment;
use TFM\HolidaysManagement\Department\Application\Create\CreateDepartmentRequest;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Mail\Domain\Model\Service\SendEmailService;
use TFM\HolidaysManagement\Shared\Domain\Model\Event\DomainEventBus;
use TFM\HolidaysManagement\WorkPlace\Application\Create\CreateWorkPlace;
use TFM\HolidaysManagement\WorkPlace\Application\Create\CreateWorkPlaceRequest;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPosition\Application\Create\CreateWorkPosition;
use TFM\HolidaysManagement\WorkPosition\Application\Create\CreateWorkPositionRequest;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;

final class CreateStructureOnCompanySaved implements MessageHandlerInterface
{
    private SendEmailService $sendWelcomeEmailService;

    private DomainEventBus $domainEventBus;
    private GetCompany $findCompany;
    private CreateWorkPlace $createWorkPlace;
    private CreateDepartment $createDepartment;
    private CreateWorkPosition $createWorkPosition;

    public function __construct(
        SendEmailService $sendWelcomeEmailService,
        DomainEventBus $domainEventBus,
        GetCompany $findCompany,
        CreateWorkPlace $createWorkPlace,
        CreateDepartment $createDepartment,
        CreateWorkPosition $createWorkPosition

    ) {
        $this->sendWelcomeEmailService = $sendWelcomeEmailService;
        $this->domainEventBus = $domainEventBus;
        $this->findCompany = $findCompany;
        $this->createWorkPlace = $createWorkPlace;
        $this->createDepartment = $createDepartment;
        $this->createWorkPosition = $createWorkPosition;
    }

    public function __invoke(CompanySavedDomainEvent $event): void
    {
        $company = ($this->findCompany)(new GetCompanyRequest($event->aggregateId()->value()));

        $workPlace = ($this->createWorkPlace)(new CreateWorkPlaceRequest(
            WorkPlace::DEFAULT_WORK_PLACE_NAME,
            WorkPlace::DEFAULT_WORK_PLACE_DESCRIPTION,
            $company->phoneNumber1(),
            $company->phoneNumber2(),
            $company->email(),
            false,
            0,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
            $company->streetName(),
            $company->number(),
            $company->floor(),
            false,
            $company,
            $company->postalCodes()->value()
        ));

        $department = ($this->createDepartment)(new CreateDepartmentRequest(
            Department::DEFAULT_DEPARTMENT_NAME,
            Department::DEFAULT_DEPARTMENT_DESCRIPTION,
            $company->phoneNumber1(),
            0,
            $workPlace->id()->value(),
            true
        ));

        ($this->createWorkPosition)(new CreateWorkPositionRequest(
            WorkPosition::DEFAULT_WORK_POSITION_NAME,
            false,
            $department
        ));

    }
}
