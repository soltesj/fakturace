<?php

namespace App\Repository;

use App\Entity\BankAccount;
use App\Entity\BankAccountBalance;
use App\Entity\Company;
use App\Status\StatusValues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BankAccount>
 *
 * @method BankAccountBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccountBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccountBalance[]    findAll()
 * @method BankAccountBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccountBalance::class);
    }

    /**
     * @return BankAccountBalance[]
     */
    public function findBalances(Company $company): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('PARTIAL b.{id, balance}', 'PARTIAL bank_account.{id, shortName, name}');
        $qb->leftJoin('b.bankAccount', 'bank_account')
            ->andWhere('bank_account.company = :company')
            ->andWhere(
                $qb->expr()->eq(
                    'b.id',
                    '(SELECT MAX(b2.id) FROM App\\Entity\\BankAccountBalance b2 WHERE b2.bankAccount = b.bankAccount)'
                )
            )
            ->setParameter('company', $company)
            ->andWhere('bank_account.status = :status')
            ->setParameter('status', StatusValues::STATUS_ACTIVE);

        return $qb->getQuery()->getResult();
    }
}
