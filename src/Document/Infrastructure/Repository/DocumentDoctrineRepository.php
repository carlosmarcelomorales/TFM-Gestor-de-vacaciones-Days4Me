<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Document\Infrastructure\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Document\Domain\DocumentRepository;
use TFM\HolidaysManagement\Document\Domain\Exception\DocumentErrorToUploadException;
use TFM\HolidaysManagement\Document\Domain\Exception\DocumentServiceIsNotAvailableException;
use TFM\HolidaysManagement\Document\Domain\Model\Aggregate\Document;

final class DocumentDoctrineRepository implements DocumentRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function repository(): object
    {
        return $this->entityManager->getRepository(Document::class);
    }

    public function upload(string $documentPathUpload, string $documentPathNew): void
    {
        try {
            move_uploaded_file($documentPathUpload, $documentPathNew);
        } catch (Exception $exception) {
            throw  new DocumentErrorToUploadException();
        }
    }

    public function save(Document $document): void
    {
        try {
            $this->entityManager->persist($document);
            $this->entityManager->flush();

        } catch (Exception $exception) {
            throw  new DocumentServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('d');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('d.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('name', $filters) && ($name = $filters['name'])) {
            $qb
                ->andWhere('d.name = :name')
                ->setParameter('name', $name);
        }


        return $qb->getQuery()->getResult();
    }

}