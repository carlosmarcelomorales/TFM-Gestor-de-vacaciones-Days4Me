<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\StatusRequest\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyIdNotExistsException;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyServiceIsNotAvailableException;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\StatusRequest\Domain\Model\Aggregate\StatusRequest;
use TFM\HolidaysManagement\StatusRequest\Domain\StatusRequestRepository;

class DoctrineStatusRequestRepository implements StatusRequestRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function ofId(IdentUuid $id): ?StatusRequest
    {
        return $this->repository()->find($id);
    }

    public function ofIdOrFail(IdentUuid $id): ?StatusRequest
    {
        $statusRequest = $this->repository()->find($id);
        if (null === $statusRequest) {
            throw  new CompanyIdNotExistsException($id->value());
        }
        return $statusRequest;
    }

    public function save(StatusRequest $statusRequest): void
    {
        try {
            $this->entityManager->persist($statusRequest);
            $this->entityManager->flush();

        } catch (\Exception $e) {
            throw new CompanyServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('sr');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('sr.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters) && ($name = $filters['name'])) {
            $qb
                ->andWhere('sr.name = :name')
                ->setParameter('name', $name);
        }

        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(StatusRequest::class);
    }

}
