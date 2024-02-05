<?php

namespace App\Repository;

use App\Entity\BankAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BankAccount>
 *
 * @method BankAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccount[]    findAll()
 * @method BankAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccount::class);
    }

    public function findByCompany($company, $display = true, $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('bank_account')
            ->andWhere('bank_account.company = :company')
            ->setParameter('company', $company);
//            ->andWhere('bank_account.display = :display')
//            ->setParameter('display', $display);
        $qb->orderBy('bank_account.sequence', $order);

        return $qb->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?BankAccount
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
