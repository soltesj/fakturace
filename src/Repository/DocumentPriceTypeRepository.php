<?php

namespace App\Repository;

use App\Entity\DocumentPriceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentPriceType>
 *
 * @method DocumentPriceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentPriceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentPriceType[]    findAll()
 * @method DocumentPriceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentPriceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentPriceType::class);
    }

//    /**
//     * @return DocumentPriceType[] Returns an array of DocumentPriceType objects
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

//    public function findOneBySomeField($value): ?DocumentPriceType
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
