<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\DocumentNumbers;
use App\Entity\DocumentType;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentNumbers>
 *
 * @method DocumentNumbers|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentNumbers|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentNumbers[]    findAll()
 * @method DocumentNumbers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentNumbersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentNumbers::class);
    }


    public function findOneByCompanyDocumentTypeYear(
        Company $company,
        DocumentType $documentType,
        int $year
    ): DocumentNumbers {
        $qb = $this->createQueryBuilder('document_numbers')
            ->andWhere('document_numbers.company = :company')
            ->setParameter('company', $company);
        $qb->andWhere('document_numbers.documentType in (:documentType)')
            ->setParameter('documentType', $documentType);
        $qb->andWhere('document_numbers.year = :year')
            ->setParameter('year', $year);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param array<DocumentType|int> $documentType
     * @return DocumentNumbers[]
     */
    public function findByCompanyDocumentTypeYear(Company $company, array $documentType, int $year): array
    {
        return $this->createQueryBuilder('document_numbers')
            ->andWhere('document_numbers.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document_numbers.documentType in (:documentType)')
            ->setParameter('documentType', $documentType)
            ->andWhere('document_numbers.year = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
