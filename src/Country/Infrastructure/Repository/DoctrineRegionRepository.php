<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Region;
use TFM\HolidaysManagement\Country\Domain\RegionRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class DoctrineRegionRepository implements RegionRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function ofId(IdentUuid $id): Region
    {
        return $this->repository()->find($id);
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('r')
            ->addSelect('c')
            ->innerJoin('r.countries', 'c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('r.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('country_id', $filters) && ($countryId = $filters['country_id'])) {
            $qb
                ->andWhere('c.id = :country_id')
                ->setParameter('country_id', $countryId);
        }


        return $qb->getQuery()->getResult();
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(Region::class);
    }

}
