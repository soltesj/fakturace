<?php

namespace App\Repository;

use App\Entity\BankAccount;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\Payment;
use App\Enum\PaymentType;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 *
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @return Payment[]
     */
    public function findMatchingPayment(
        Company $company,
        PaymentType $paymentType,
        float $price,
        ?DateTimeImmutable $date,
        ?Document $document,
        ?BankAccount $bankAccount,
        ?string $vs,
        ?string $ks,
        ?string $ss,
        ?string $counterparty,
        ?string $message,
        ?string $transactionId
    ): array {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.company = :company')
            ->andWhere('p.type = :type')
            ->andWhere('p.price = :price')
            ->setParameter('company', $company)
            ->setParameter('type', $paymentType)
            ->setParameter('price', $price);
        if ($date !== null) {
            $qb->andWhere('p.date = :date')
                ->setParameter('date', $date);
        }
        if ($document !== null) {
            $qb->andWhere('p.document = :document')
                ->setParameter('document', $document);
        } else {
            $qb->andWhere('p.document IS NULL');
        }
        if ($bankAccount !== null) {
            $qb->andWhere('p.bankAccount = :bankAccount')
                ->setParameter('bankAccount', $bankAccount);
        } else {
            $qb->andWhere('p.bankAccount IS NULL');
        }
        $qb->andWhere('p.variableSymbol '.($vs !== null ? '= :vs' : 'IS NULL'));
        $qb->andWhere('p.constantSymbol '.($ks !== null ? '= :ks' : 'IS NULL'));
        $qb->andWhere('p.specificSymbol '.($ss !== null ? '= :ss' : 'IS NULL'));
        $qb->andWhere('p.counterAccount '.($counterparty !== null ? '= :counterparty' : 'IS NULL'));
        $qb->andWhere('p.message '.($message !== null ? '= :message' : 'IS NULL'));
        $qb->andWhere('p.transactionId '.($transactionId !== null ? '= :transactionId' : 'IS NULL'));
        if ($vs !== null) {
            $qb->setParameter('vs', $vs);
        }
        if ($ks !== null) {
            $qb->setParameter('ks', $ks);
        }
        if ($ss !== null) {
            $qb->setParameter('ss', $ss);
        }
        if ($counterparty !== null) {
            $qb->setParameter('counterparty', $counterparty);
        }
        if ($message !== null) {
            $qb->setParameter('message', $message);
        }
        if ($transactionId !== null) {
            $qb->setParameter('transactionId', $transactionId);
        }

        return $qb->getQuery()->getResult();///getOneOrNullResult();
    }

    public function getNetPaymentSumForDocument(Document $document): float
    {
        $qb = $this->createQueryBuilder('p')
            ->select('SUM(CASE WHEN p.type = :income THEN p.price ELSE 0 END) - SUM(CASE WHEN p.type = :expense THEN p.price ELSE 0 END) AS netTotal')
            ->where('p.document = :document')
            ->setParameter('document', $document)
            ->setParameter('income', PaymentType::INCOME)
            ->setParameter('expense', PaymentType::EXPENSE);

        return (float)$qb->getQuery()->getSingleScalarResult();
    }


//    /**
//     * @return Payment[] Returns an array of Payment objects
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
//    public function findOneBySomeField($value): ?Payment
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
