<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Holiday\Infrastructure\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayIdNotExistsException;
use TFM\HolidaysManagement\Holiday\Domain\Exception\HolidayServiceIsNotAvailableException;
use TFM\HolidaysManagement\Holiday\Domain\HolidayRepository;
use TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

final class HolidayDoctrineRepository implements HolidayRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Holiday $holiday): void
    {
        try {
            $this->entityManager->persist($holiday);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw  new HolidayServiceIsNotAvailableException();
        }
    }

    public function getAllHolidays(array $orderBy): ?array
    {
        try {
            return $this->entityManager->getRepository(Holiday::class)->findAll();
        } catch (Exception $exception) {
            throw new HolidayServiceIsNotAvailableException();
        }
    }

    public function ofIdOrNull(IdentUuid $id): Holiday
    {
        $holiday = $this->entityManager->getRepository(Holiday::class)->find($id);

        if (null === $holiday) {
            throw new HolidayIdNotExistsException($id->value());
        }
        return $holiday;
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('h')
            ->join('h.workPlaces', 'wp')
            ->join('wp.companies', 'c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('h.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters) && ($name = $filters['name'])) {
            $qb
                ->andWhere('h.name = :name')
                ->setParameter('name', $filters['name']);
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

        if (array_key_exists('dateStart', $filters) && ($dateStart = $filters['dateStart'])) {
            $qb
                ->andWhere('h.startDay >= :dateStart')
                ->setParameter('dateStart', $dateStart);
        }

        if (array_key_exists('dateEnd', $filters) && ($dateEnd = $filters['dateEnd'])) {
            $qb
                ->andWhere('h.endDay <= :dateEnd')
                ->setParameter('dateEnd', $dateEnd);
        }

        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(Holiday::class);
    }

    public function rangeHolidaysToRequest(array $filters): array
    {
        $dql = "SELECT h FROM TFM\HolidaysManagement\Holiday\Domain\Model\Aggregate\Holiday h  
                    WHERE (h.startDay >= :dateStart AND h.endDay >= :dateEnd) 
                       OR (h.startDay >= :dateStart AND h.endDay <= :dateEnd)
                       ORDER BY h.startDay ASC 
                       ";

        $parameters = [
            'dateStart' => $filters['dateStart'],
            'dateEnd' => $filters['dateEnd']
        ];

        return $this->entityManager->createQuery($dql)
            ->setParameters($parameters)
            ->getResult();
    }

    public function getHolidayById(IdentUuid $id): ?Holiday
    {
        try {
            return $this->entityManager->getRepository(Holiday::class)->find($id);
        } catch (Exception $exception) {
            throw new HolidayServiceIsNotAvailableException();
        }
    }
}
