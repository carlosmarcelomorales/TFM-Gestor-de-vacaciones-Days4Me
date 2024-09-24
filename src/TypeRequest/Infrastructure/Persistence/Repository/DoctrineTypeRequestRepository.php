<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\TypeRequest\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyIdNotExistsException;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyServiceIsNotAvailableException;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\TypeRequest\Domain\Model\Aggregate\TypeRequest;
use TFM\HolidaysManagement\TypeRequest\Domain\TypeRequestRepository;

class DoctrineTypeRequestRepository implements TypeRequestRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function ofIdOrFail(IdentUuid $id): ?TypeRequest
    {
        $typeRequest = $this->repository()->find($id);
        if (null === $typeRequest) {
            throw  new CompanyIdNotExistsException($id->value());
        }
        return $typeRequest;
    }

    public function save(TypeRequest $typeRequest): void
    {
        try {
            $this->entityManager->persist($typeRequest);
            $this->entityManager->flush();

        } catch (\Exception $e) {
            throw new CompanyServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('tr');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('tr.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters) && ($name = $filters['name'])) {
            $qb
                ->andWhere('tr.name = :name')
                ->setParameter('name', $name);
        }

        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(TypeRequest::class);
    }

    public function getAll(): array
    {
        try {
            return $this->repository()->findAll();
        } catch (\Exception $e) {

        }
    }
}
