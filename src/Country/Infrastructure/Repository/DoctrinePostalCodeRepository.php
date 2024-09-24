<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Country\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TFM\HolidaysManagement\Country\Domain\Exception\PostalCodeNotExistsException;
use TFM\HolidaysManagement\Country\Domain\Model\Aggregate\Region\PostalCode\PostalCode;
use TFM\HolidaysManagement\Country\Domain\PostalCodeRepository;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;

class DoctrinePostalCodeRepository implements PostalCodeRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function ofValueOrDefault(string $value): PostalCode
    {
        $postalCode = $this->repository()->findOneBy(['value' => $value]);
        if (null === $postalCode) {
            $postalCode = $this->repository()->findOneBy([PostalCode::DEFAULT_NOT_POSTAL_CODE]);
        }
        return $postalCode;
    }

    public function ofValueOrFail(string $value): PostalCode
    {
        $postalCode = $this->repository()->findOneBy(['value' => $value]);
        if (null === $postalCode) {
            throw new PostalCodeNotExistsException($value);
        }
        return $postalCode;
    }

    public function ofTownId(IdentUuid $townId): array
    {
        return $this->repository()
            ->createQueryBuilder('pc')
            ->innerJoin('pc.towns', 't')
            ->andWhere('t.id = :townId')
            ->setParameter('townId', $townId->value())
            ->orderBy('pc.value', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(PostalCode::class);
    }

}
