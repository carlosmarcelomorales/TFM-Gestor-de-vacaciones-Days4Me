<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Role\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TFM\HolidaysManagement\Role\Domain\Exception\RoleNotExistsException;
use TFM\HolidaysManagement\Role\Domain\Model\Aggregate\Role;
use TFM\HolidaysManagement\Role\Domain\RoleRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class RolesDoctrineRepository implements RoleRepository
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll(): ?array
    {

        try {
            $roles = $this->entityManager->getRepository(Role::class)->findAll();
        } catch (\Exception $e) {
            echo 'Not enough roles: ', $e->getMessage();
        }

        return $roles;
    }

    public function getOne(IdentUuid $roleId): Role
    {

        return $this->entityManager->getRepository(Role::class)->find($roleId);

    }

    public function findByName(string $role): Role
    {
        return $this->entityManager->getRepository(Role::class)->findOneBy(['name' => $role]);
    }

    public function findById(IdentUuid $id): Role
    {
        return $this->entityManager->getRepository(Role::class)->find($id->value());
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('r');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('r.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters) && ($name = $filters['name'])) {
            $qb
                ->andWhere('r.name = :name')
                ->setParameter('name', $name);
        }

        if (array_key_exists('no_name', $filters) && ($no_name = $filters['no_name'])) {
            $qb
                ->andWhere('r.name != :no_name')
                ->setParameter('no_name', $no_name);
        }

        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(Role::class);
    }


    public function ofAllFiltered(array $filter): array
    {
        $qb = $this->entityManager->getRepository(Role::class)
            ->createQueryBuilder('r');

        if (array_key_exists('id', $filter) && !empty($filter['id'])) {
            $qb
                ->andWhere('r.id = :id')
                ->setParameter('id', $filter['id']);
        }

        if (array_key_exists('name', $filter) && !empty($filter['name'])) {
            $qb
                ->andWhere('c.name = :name')
                ->setParameter('name', $filter['name']);
        }


        return $qb->getQuery()->getResult();
    }

    public function ofFilteredOrNull(array $filter): Role
    {
        $role = $this->entityManager->getRepository(Role::class)->findOneBy($filter);
        if (null === $role) {
            throw new RoleNotExistsException(implode(',', $filter));
        }
        return $role;
    }

}
