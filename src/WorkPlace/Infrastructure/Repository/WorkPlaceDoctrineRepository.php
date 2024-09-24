<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPlace\Infrastructure\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPlace\Domain\Exception\WorkPlaceServiceIsNotAvailableException;
use TFM\HolidaysManagement\WorkPlace\Domain\Model\Aggregate\WorkPlace;
use TFM\HolidaysManagement\WorkPlace\Domain\WorkPlaceRepository;

final class WorkPlaceDoctrineRepository implements WorkPlaceRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(WorkPlace $workPlace): void
    {
        try {
            $this->entityManager->persist($workPlace);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw new WorkPlaceServiceIsNotAvailableException();
        }
    }

    public function getByCompany(IdentUuid $companyId): ?array
    {
        $company = $this->repository()->find($companyId);

        $workplaces = $this->entityManager->getRepository(WorkPlace::class)->findBy(['companies' => $company]);

        return $workplaces;
    }

    public function ofIdOrFail(IdentUuid $workPlace): ?WorkPlace
    {
        $workplace = $this->repository()->find($workPlace);

        if (null === $workplace) {
            throw  new InvalidArgumentException('Work Place not exists!!');
        }

        return $workplace;
    }

    private function repository()
    {
        return $this->entityManager->getRepository(WorkPlace::class);
    }

    public function getAllWorkPlaces(array $orderBy): ?array
    {
        try {
            return $this->entityManager->getRepository(WorkPlace::class)->findAll();
        } catch (Exception $exception) {
            throw new WorkPlaceServiceIsNotAvailableException();
        }
    }

    public function getWorkPlaceById(IdentUuid $workPlaceId): ?WorkPlace
    {
        try {
            return $this->entityManager->getRepository(WorkPlace::class)->find($workPlaceId);
        } catch (Exception $exception) {
            throw new WorkPlaceServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('w')
            ->innerJoin('w.companies','c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('w.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('blocked', $filters) && ($blocked = $filters['blocked'])) {
            $qb
                ->andWhere('w.blocked = :blocked')
                ->setParameter('blocked', $blocked);
        }

        if (array_key_exists('holidayEndYear', $filters) && ($holidayEndYear = $filters['holidayEndYear'])) {
            $qb
                ->andWhere('w.holidayEndYear = :holidayEndYear')
                ->setParameter('holidayEndYear', $holidayEndYear);
        }


        if (array_key_exists('company', $filters) && ($company = $filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $company);
        }


        return $qb->getQuery()->getResult();
    }


}
