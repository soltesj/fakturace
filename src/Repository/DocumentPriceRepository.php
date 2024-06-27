<?php

namespace App\Repository;

use App\Entity\DocumentPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentPrice>
 *
 * @method DocumentPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentPrice[]    findAll()
 * @method DocumentPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentPrice::class);
    }

//    /**
//     * @return DocumentPrice[] Returns an array of DocumentPrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DocumentPrice
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
