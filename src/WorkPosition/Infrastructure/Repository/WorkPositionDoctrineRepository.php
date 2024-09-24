<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\WorkPosition\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\WorkPosition\Domain\Exception\WorkPositionServiceIsNotAvailableException;
use TFM\HolidaysManagement\WorkPosition\Domain\Model\Aggregate\WorkPosition;
use TFM\HolidaysManagement\WorkPosition\Domain\WorkPositionRepository;

final class WorkPositionDoctrineRepository implements WorkPositionRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(WorkPosition $workPosition): void
    {
        try {
            $this->entityManager->persist($workPosition);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw  new WorkPositionServiceIsNotAvailableException();
        }
    }

    public function getAllByDepartment(?array $departments): ?array
    {
        try {
            if (!empty($departments)) {
                $totalWorkPositions = [];

                foreach ($departments as $department) {
                    $workPositions = $this->entityManager->getRepository(WorkPosition::class)->findBy(['departments' => $department]);

                    foreach ($workPositions as $workPosition) {
                        $totalWorkPositions[] = $workPosition;
                    }
                }

                return $totalWorkPositions;
            }
        } catch (Exception $exception) {
            throw new WorkPositionServiceIsNotAvailableException();
        }
    }

    public function getWorkPositionById(IdentUuid $id): ?WorkPosition
    {
        try {
            return $this->entityManager->getRepository(WorkPosition::class)->find($id);
        } catch (Exception $exception) {
            throw new WorkPositionServiceIsNotAvailableException();
        }
    }

    public function ofId(IdentUuid $workPosition): ?WorkPosition
    {
        return $this->repository()->find($workPosition);
    }

    public function ofOneCompanyId(IdentUuid $companyId): ?WorkPosition
    {
        return $this->repository()
            ->createQueryBuilder('wc')
            ->innerJoin('wc.departments', 'd')
            ->innerJoin('d.workPlace', 'wd')
            ->innerJoin('wd.companies', 'c')
            ->andWhere('c.id = :companyId')
            ->setParameter('companyId', $companyId->value())
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(WorkPosition::class);
    }

    public function getAllWorkPositions(array $orderBy): ?array
    {
        try {
            return $this->entityManager->getRepository(WorkPosition::class)->findAll();
        } catch (Exception $exception) {
            throw new WorkPositionServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('w')
            ->join('w.departments', 'd')
            ->join('d.workPlace', 'wp')
            ->join('wp.companies', 'c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('w.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('headDepartment', $filters) && ($headDepartment = $filters['headDepartment'])) {
            $qb
                ->andWhere('w.headDepartment = :headDepartment')
                ->setParameter('headDepartment', $headDepartment);
        }

        if (array_key_exists('department', $filters) && ($department = $filters['department'])) {
            $qb
                ->andWhere('d.id = :department')
                ->setParameter('department', $department);
        }

        if (array_key_exists('workPlace', $filters) && ($workPlace = $filters['workPlace'])) {
            $qb
                ->andWhere('wp.id = :workPlace')
                ->setParameter('workPlace', $workPlace);
        }

        if (array_key_exists('company', $filters) && !empty($filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $filters['company']);
        }

        return $qb->getQuery()->getResult();
    }
}
