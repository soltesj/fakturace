<?php

namespace App\Repository;

use App\Entity\DocumentPaymentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentPaymentType>
 *
 * @method DocumentPaymentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentPaymentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentPaymentType[]    findAll()
 * @method DocumentPaymentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentPaymentType::class);
    }

//    /**
//     * @return PaymentType[] Returns an array of PaymentType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PaymentType
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
