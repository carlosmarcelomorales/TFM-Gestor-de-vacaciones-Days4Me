<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\User\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use InvalidArgumentException;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Exception\UsersCompaniesNotAvailableException;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;
use TFM\HolidaysManagement\User\Domain\Model\Exception\UserNotExistsException;
use TFM\HolidaysManagement\User\Domain\UserRepository;

final class UserDoctrineRepository implements UserRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findEmail(string $email): ?User
    {
        return $this
            ->repository()
            ->findOneBy(['emailAddress' => $email]);
    }

    public function save(User $user): void
    {

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        } catch (Exception $e) {
            throw new InvalidArgumentException(sprintf('Could not persist!! : %s!!!', $e->getMessage()));
        }
    }

    public function findOneByEmail(string $email): ?User
    {
        $user = $this->repository()->findOneBy(['emailAddress' => $email]);
        if (null === $user) {
            throw new UserNotExistsException($email);
        }
        return $user;
    }

    public function getAllByCompanyId(IdentUuid $companyId): array
    {
        try {
            return $this->repository()->findBy(['companies' => $companyId]);
        } catch (Exception $e) {
            throw new UsersCompaniesNotAvailableException();
        }
    }

    public function ofFilteredOrNull(array $filter): User
    {
        $user = $this->repository()->findOneBy($filter);
        if (null === $user) {
            throw new UserNotExistsException(implode(',', $filter));
        }
        return $user;
    }

    public function ofTokenOrFail(string $token): ?User
    {
        $user = $this->repository()->findOneBy(['tokenRecovery' => $token]);
        if (null === $user) {
            throw new UsersCompaniesNotAvailableException();
        }
        return $user;
    }


    public function findToken(string $token): ?User
    {
        $user = $this->repository()->findOneBy(['tokenRecovery' => $token]);
        if (null === $user) {
            throw new UsersCompaniesNotAvailableException();
        }
        return $user;
    }

    public function ofIdOrFail(IdentUuid $id): ?User
    {
        $user = $this->repository()->find($id);
        if (null === $user) {
            throw  new UserNotExistsException($id->value());
        }

        return $user;
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('u')
            ->join('u.workPositions', 'wpn')
            ->join('wpn.departments', 'd')
            ->join('d.workPlace', 'wp')
            ->join('wp.companies', 'c');


        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('u.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('dni', $filters) && ($dni = $filters['dni'])) {
            $qb
                ->andWhere('u.dni = :dni')
                ->setParameter('dni', $dni);
        }

        if (array_key_exists('workPlace', $filters) && ($workPlace = $filters['workPlace'])) {
            $qb
                ->andWhere('wp.id = :workPlace')
                ->setParameter('workPlace', $workPlace);
        }

        if (array_key_exists('workPosition', $filters) && ($workPosition = $filters['workPosition'])) {
            $qb
                ->andWhere('wpn.id = :workPosition')
                ->setParameter('workPosition', $workPosition);
        }


        if (array_key_exists('company', $filters) && ($company = $filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $company);
        }


        return $qb->getQuery()->getResult();
    }

    private function repository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }

    public function allFilters(array $filters): ?array
    {
        $qb = $this->repository()
            ->createQueryBuilder('u')
            ->innerJoin('u.departments', 'd')
            ->innerJoin('u.workPositions', 'wp')
            ->innerJoin('u.companies', 'c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('u.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('headDepartment', $filters) && !empty($filters['headDepartment'])) {
            $qb
                ->andWhere('wp.headDepartment = :headDepartment')
                ->setParameter('headDepartment', $filters['headDepartment']);
        }

        if (array_key_exists('department', $filters) && !empty($filters['department'])) {
            $qb
                ->andWhere('d.id = :department')
                ->setParameter('department', $filters['department']);
        }

        if (array_key_exists('emailAddress', $filters) && !empty($filters['emailAddress'])) {
            $qb
                ->andWhere('u.emailAddress = :emailAddress')
                ->setParameter('emailAddress', $filters['emailAddress']);
        }

        if (array_key_exists('company', $filters) && !empty($filters['company'])) {
            $qb
                ->andWhere('c.id = :company')
                ->setParameter('company', $filters['company']);
        }

        return $qb->getQuery()->getResult();
    }
}
