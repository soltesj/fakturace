<?php

namespace App\Repository;

use App\Entity\CompanyInbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanyInbox>
 *
 * @method CompanyInbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyInbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyInbox[]    findAll()
 * @method CompanyInbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyInboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyInbox::class);
    }

//    /**
//     * @return CompanyInbox[] Returns an array of CompanyInbox objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    public function findOneBySomeField($value): ?CompanyInbox
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
