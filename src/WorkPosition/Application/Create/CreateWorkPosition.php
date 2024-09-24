<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Create;

use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class CreateWorkPosition
{
    private WorkPositionRepository $repository;

    public function __construct(WorkPositionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateWorkPositionRequest $workPositionRequest): WorkPosition
    {
        $workPosition = WorkPosition::create(
            new IdentUuid(),
            $workPositionRequest->name(),
            $workPositionRequest->headDepartment(),
            $workPositionRequest->department(),
            new DateTimeImmutable(),
            null
        );

        $this->repository->save($workPosition);
        return $workPosition;
    }
}
