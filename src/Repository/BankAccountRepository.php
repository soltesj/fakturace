<?php

namespace App\Repository;

use App\Entity\BankAccount;
use App\Entity\Company;
use App\Status\StatusValues;
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

    /**
     * @return BankAccount[]
     */
    public function findByCompany(Company $company, int $statusId = StatusValues::STATUS_ACTIVE, string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('bank_account')
            ->andWhere('bank_account.company = :company')
            ->setParameter('company', $company)
            ->andWhere('bank_account.status = :status')
            ->setParameter('status', $statusId);
        $qb->orderBy('bank_account.sequence', $order);

        return $qb->getQuery()->getResult();
    }


    public function findActiveByAccountNumberAndInboxIdentifier(
        string $accountNumber,
        string $identifier,
        int $statusId = 1
    ): ?BankAccount {
        $query = $this->createQueryBuilder('bank_account')
            ->leftJoin('bank_account.company', 'company')
            ->leftJoin('company.identifiers', 'company_identifiers')
            ->where('bank_account.accountNumber = :accountNumber')
            ->andWhere('bank_account.status = :statusId')
            ->andWhere('company_identifiers.identifier = :identifier')
            ->setParameter('accountNumber', $accountNumber)
            ->setParameter('statusId', $statusId)
            ->setParameter('identifier', $identifier)
            ->getQuery();

        return $query->getOneOrNullResult();
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
