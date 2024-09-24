<?php
declare(strict_types=1);

namespace TFM\HolidaysManagement\Company\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use TFM\HolidaysManagement\Company\Domain\CompanyRepository;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyIdNotExistsException;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyServiceIsNotAvailableException;
use TFM\HolidaysManagement\Company\Domain\Exception\CompanyVatNotExistsException;
use TFM\HolidaysManagement\Company\Domain\Model\Aggregate\Company;
use TFM\HolidaysManagement\Shared\Domain\ValueObject\IdentUuid;
use TFM\HolidaysManagement\User\Domain\Model\Aggregate\User;

class DoctrineCompanyRepository implements CompanyRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function AllCompanies(array $orderBy): ?array
    {
        try {
            return $this->repository()->findAll();
        } catch (Exception $e) {
            throw new CompanyServiceIsNotAvailableException();
        }
    }

    public function ofVat(string $vat): ?Company
    {
        return $this->repository()->findOneBy(['vat' => $vat]);
    }

    public function ofVatOrFail(string $vat): ?Company
    {
        $company = $this->repository()->findOneBy(['vat' => $vat]);
        if (null === $company) {
            throw  new CompanyVatNotExistsException($vat);
        }
        return $company;
    }

    public function ofUserOrFail(User $user): ?Company
    {
        $company = $this->repository()->findOneBy(['user' => $user]);
        if (null === $company) {
            throw  new CompanyIdNotExistsException($user->id()->value());
        }
        return $company;
    }


    public function ofIdOrFail(IdentUuid $id): ?Company
    {
        $company = $this->repository()->find($id);
        if (null === $company) {
            throw  new CompanyIdNotExistsException($id->value());
        }
        return $company;
    }

    public function save(Company $company): void
    {
        try {
            $this->entityManager->persist($company);
            $this->entityManager->flush();

        } catch (\Exception $e) {
            throw new CompanyServiceIsNotAvailableException();
        }
    }

    public function allFiltered(array $filters): array
    {
        $qb = $this->repository()
            ->createQueryBuilder('c');

        if (array_key_exists('id', $filters) && !empty($filters['id'])) {
            $qb
                ->andWhere('c.id = :id')
                ->setParameter('id', $filters['id']);
        }

        if (array_key_exists('vat', $filters) && ($vat = $filters['vat'])) {
            $qb
                ->andWhere('c.vat = :vat')
                ->setParameter('vat', $vat);
        }


        return $qb->getQuery()->getResult();
    }

    private function repository()
    {
        return $this->entityManager->getRepository(Company::class);
    }

}
