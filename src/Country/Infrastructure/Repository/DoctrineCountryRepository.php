<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use TFM\HolidaysManagement\Country\Domain\CountryRepository;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Country;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class DoctrineCountryRepository implements CountryRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function allSorted(array $orderBy): array
    {
        $qb = $this->repository()->createQueryBuilder('c');

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $qb->addOrderBy(sprintf('c.%s', $field), $direction);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function ofId(IdentUuid $id): Country
    {
        return $this->repository()->find($id);
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(Country::class);
    }
}
