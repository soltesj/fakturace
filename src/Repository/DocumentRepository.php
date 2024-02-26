<?php

namespace App\Repository;

use App\Entity\Document;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
     * @return ?Document[] Returns an array of Document objects
     * @throws Exception
     */
    public function findByCompany(
        $company,
        $documentType = [],
        $order = 'DESC',
        ?DateTime $from = null,
        ?DateTime $to = null
    ): ?array {
        if ($from === null) {
            $from = new DateTime((new DateTime())->format('Y').'-01-01 00:00:00');
        }
        $qb = $this->createQueryBuilder('document')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.documentType in (:documentType)')
            ->setParameter('documentType', $documentType)
            ->andWhere('document.dateIssue >= (:dateIssueFrom)')
            ->setParameter('dateIssueFrom', $from);
        if($to!==null){
            $qb->andWhere('document.dateIssue <= (:dateIssueTo)')
                ->setParameter('dateIssueTo', $to);
        }
        $qb->orderBy('document.dateIssue', $order)
            ->orderBy('document.id', $order);

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
