<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Request\Infrastructure\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestIdNotExistsException;
use TFM\HolidaysManagement\Request\Domain\Exception\RequestServiceIsNotAvailableException;
use TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request;
use TFM\HolidaysManagement\Request\Domain\RequestRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class RequestDoctrineRepository implements RequestRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(Request::class);
    }

    public function save(Request $request): void
    {
        try {
            $this->entityManager->persist($request);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw  new RequestServiceIsNotAvailableException();
        }
    }


    public function getAllByUser(IdentUuid $id): ?array
    {
        try {
            return $this->repository()->findBy(['users' => $id]);
        } catch (Exception $exception) {
            throw new RequestServiceIsNotAvailableException();
        }
    }

    public function getRequestById(IdentUuid $id): ?Request
    {
        try {
            return $this->repository()->find($id);
        } catch (Exception $exception) {
            throw new RequestIdNotExistsException($id->value());
        }
    }

    public function ofIdOrFail(IdentUuid $id): Request
    {
        $requests = $this->repository()->find($id);
        if (null === $requests) {
            throw new RequestServiceIsNotAvailableException();
        }
        return $requests;
    }

    public function allFiltered(array $filters): ?array
    {

        $qb = $this->repository()
            ->createQueryBuilder('r')
            ->addSelect('r')
            ->innerJoin('r.users', 'u')
            ->innerJoin('u.roles', 'rl')
            ->innerJoin('u.companies', 'c')
            ->innerJoin('r.typesRequest', 'tr')
            ->innerJoin('r.statusRequest', 'sru');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('r.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('user', $filters) && !empty($filters['user'])) {
            $qb
                ->andWhere('u.id = :user')
                ->setParameter('user', $filters['user']);
        }

        if (array_key_exists('typesRequest', $filters) && !empty($filters['typesRequest'])) {
            $qb
                ->andWhere('tr.id = :typesRequest')
                ->setParameter('typesRequest', $filters['typesRequest']);
        }

        if (array_key_exists('statusRequest', $filters) && !empty($filters['statusRequest'])) {
            $qb
                ->andWhere('r.id = :statusRequest')
                ->setParameter('statusRequest', $filters['statusRequest']);
        }

        if (array_key_exists('department', $filters) && !empty($filters['department'])) {
            $qb
                ->andWhere('u.departments = :department')
                ->setParameter('department', $filters['department']);
        }

        if (array_key_exists('company', $filters) && !empty($filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $filters['company']);
        }

        if (array_key_exists('roles', $filters) && !empty($filters['roles'])) {
            $qb
                ->andWhere('rl.name = :roles')
                ->setParameter('roles', $filters['roles']);
        }

        $qb->orderBy('r.requestPeriodStart', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function countStatus(array $filters): ?int
    {
        $qb = $this->repository()
            ->createQueryBuilder('r')
            ->join('r.statusRequest', 'sr');


        if (array_key_exists('status', $filters) && !empty($filters['status'])) {
            $qb
                ->andWhere('sr.name = :status')
                ->setParameter('status', $filters['status']);
        }

        if (array_key_exists('userId', $filters) && !empty($filters['userId'])) {
            $qb->andWhere('r.users = :userId')
                ->setParameter('userId', $filters['userId']);
        }

        return count($qb->getQuery()->getResult());
    }

    public function rangeRequestDays(array $filters): array
    {
        $dql = "SELECT r FROM TFM\HolidaysManagement\Request\Domain\Model\Aggregate\Request r

                 WHERE  r.statusRequest = :statusRequest 
                   AND r.users = :user 
                   AND (
                         (r.requestPeriodStart BETWEEN :dateStart AND :dateEnd) OR 
                         (r.requestPeriodStart BETWEEN :dateStart AND :dateEnd) OR 
                         (r.requestPeriodStart <= :dateStart AND r.requestPeriodEnd >= :dateEnd)
                       )
                ";

        $parameters = [
            'dateStart' => $filters['dateStart'],
            'dateEnd' => $filters['dateEnd'],
            'statusRequest' => $filters['statusRequest'],
            'user' => $filters['user']
        ];

        return $this->entityManager->createQuery($dql)
            ->setParameters($parameters)
            ->getResult();
    }


}
