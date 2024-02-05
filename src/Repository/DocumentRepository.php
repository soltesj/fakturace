<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * @return Document[] Returns an array of Document objects
     */
    public function findByCompany($company, $documentType = [], $order = 'DESC'): array
    {
        $qb = $this->createQueryBuilder('document')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company);
        $qb->andWhere('document.documentType in (:documentType)')
            ->setParameter('documentType', $documentType);
        $qb->orderBy('document.dateIssue', $order);

        return $qb->getQuery()->getResult();
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
