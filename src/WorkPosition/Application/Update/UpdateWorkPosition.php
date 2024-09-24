<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Application\Update;


use DateTimeImmutable;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Exception\WorkPositionIdNotExistsException;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class UpdateWorkPosition
{
    private WorkPositionRepository $repository;

    public function __construct(WorkPositionRepository $workPositionRepository)
    {
        $this->repository = $workPositionRepository;
    }

    public function __invoke(UpdateWorkPositionRequest $workPositionsRequest): WorkPosition
    {
        $workPosition = $this->repository->getWorkPositionById(new IdentUuid($workPositionsRequest->id()));

        if ($workPosition === null) {
            throw new WorkPositionIdNotExistsException($workPositionsRequest->id());
        }

        $workPosition->update(
            $workPositionsRequest->name(),
            $workPositionsRequest->headDepartment(),
            $workPositionsRequest->department(),
            new DateTimeImmutable()
        );

        $this->repository->save($workPosition);

        return $workPosition;
    }
}
