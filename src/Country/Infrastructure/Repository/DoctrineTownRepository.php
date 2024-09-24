<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Infrastructure\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use TFM\HolidaysManagement\Country\Domain\Exception\TownServiceIsNotAvailableException;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\Town\Town;
use TFM\HolidaysManagement\Country\Domain\TownRepository;

final class DoctrineTownRepository implements TownRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByPostalCode(string $postalCodeId): array
    {
        $query = $this->repository()
            ->createQueryBuilder('t')
            ->innerJoin('t.postalCodes', 'pc')
            ->andWhere('pc.id = :postal_code')
            ->setParameter('postal_code', $postalCodeId);

        return $this->toPrimitive($query->getQuery()->getResult());
    }

    public function toPrimitive(array $towns): array
    {
        $data = [];
        foreach ($towns as $town) {
            $data[] = [
                'id' => $town->id(),
                'name' => $town->name(),
                'region' => $town->regions()->name(),
                'country' => $town->regions()->countries()->name(),
            ];
        }
        return $data;
    }

    public function AllRegions(array $orderBy, array $limit): ?array
    {
        try {
            return
                $this->repository()
                    ->createQueryBuilder('c')
                    ->getQuery()
                    ->getResult();
        } catch (Exception $e) {
            throw new TownServiceIsNotAvailableException();
        }

    }

    public function byId(string $id): ?Town
    {
        try {
            return $this->repository()->find($id);
        } catch (Exception $e) {
            throw new TownServiceIsNotAvailableException();
        }
    }

    public function allFilteredSortedAndLimited(array $filters, array $orderBy, array $limit): array
    {
        $qb = $this->query($filters);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $direction) {
                $prefix = 't';

                if (strpos($field, 'country_') === 0) {
                    $prefix = 'c';
                    $field = str_replace('country_', '', $field);
                } elseif (strpos($field, 'region_') === 0) {
                    $prefix = 'r';
                    $field = str_replace('region_', '', $field);
                }

                $qb->addOrderBy(sprintf('%s.%s', $prefix, $field), $direction);
            }
        }

        if (!empty($limit)) {
            if (array_key_exists('limit', $limit)) {
                $qb->setMaxResults($limit['limit']);
            }

            if (array_key_exists('offset', $limit)) {
                $qb->setFirstResult($limit['offset']);
            }
        }

        return $this->getPaginator($qb);
    }

    private function query(array $filters): QueryBuilder
    {
        $qb = $this->repository()
            ->createQueryBuilder('t')
            ->addSelect('r', 'pc')
            ->innerJoin('t.regions', 'r')
            ->innerJoin('r.countries', 'c')
            ->leftJoin('t.postalCodes', 'pc');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('t.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters)) {
            $qb
                ->andWhere('t.name LIKE :name')
                ->setParameter('name', sprintf('%%%s%%', $filters['name']));
        }

        if (array_key_exists('country_id', $filters) && ($countryId = $filters['country_id'])) {


            $qb
                ->andWhere('c.id = :country_id')
                ->setParameter('country_id', $countryId);
        }

        if (array_key_exists('region_id', $filters) && ($regionId = $filters['region_id'])) {


            $qb
                ->andWhere('r.id = :region_id')
                ->setParameter('region_id', $regionId);
        }

        return $qb;
    }

    private function getPaginator(QueryBuilder $qb): array
    {
        // This code is necessary, sometimes, when the query is complex and there
        // are several equal records that make the paginator not work properly
        $paginator = new Paginator($qb);

        $elements = [];

        foreach ($paginator as $item) {
            $elements[] = $item;
        }

        return $elements;
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(Town::class);
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('r')
            ->addSelect('c')
            ->innerJoin('r.regions', 'c');

        if (!empty($filters['region_id'])){
            $qb
                ->andWhere('c.id = :region_id')
                ->setParameter('region_id', $filters['region_id']);
        }

        return $qb->getQuery()->getResult();

    }
}
