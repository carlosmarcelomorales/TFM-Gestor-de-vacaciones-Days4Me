<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Department\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Department\Domain\DepartmentRepository;
use TFM\HolidaysManagement\Department\Domain\Exception\DepartmentServiceIsNotAvailableException;
use TFM\HolidaysManagement\Department\Domain\Model\Aggregate\Department;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class DepartmentDoctrineRepository implements DepartmentRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Department $department): void
    {
        try {
            $this->entityManager->persist($department);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw  new DepartmentServiceIsNotAvailableException();
        }
    }


    public function getAllByWorkplace(?array $workplaces): array
    {
        if (!empty($workplaces)) {
            $totalDepartments = [];
            foreach ($workplaces as $workplace) {
                $departments = $this->repository()->findBy(['workPlace' => $workplace]);

                foreach ($departments as $department) {
                    $totalDepartments[] = $department;
                }

            }

            return $totalDepartments;
        }

    }

    public function getAllDepartments(array $orderBy): ?array
    {
        try {
            return $this->repository()->findAll();
        } catch (Exception $exception) {
            throw new DepartmentServiceIsNotAvailableException();
        }
    }

    public function getDepartmentById(IdentUuid $id): ?Department
    {
        try {
            return $this->repository()->find($id);
        } catch (Exception $exception) {
            throw new DepartmentServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('d')
            ->join('d.workPlace', 'wp')
            ->join('wp.companies', 'c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('d.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('active', $filters) && ($active = $filters['active'])) {
            $qb
                ->andWhere('d.active = :active')
                ->setParameter('active', $active);
        }

        if (array_key_exists('workPlace', $filters) && ($workPlace = $filters['workPlace'])) {
            $qb
                ->andWhere('wp.id = :workPlace')
                ->setParameter('workPlace', $workPlace);
        }

        if (array_key_exists('company', $filters) && ($company = $filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $company);
        }

        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(Department::class);
    }

}
