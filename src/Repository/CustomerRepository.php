<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */
    public function findByCompany(Company $company, int $statusId = 1, string $order = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('customer')
            ->andWhere('customer.company = :company')
            ->setParameter('company', $company)
            ->andWhere('customer.status = :status')
            ->setParameter('status', $statusId);
        $qb->orderBy('customer.name', $order);

        return $qb->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
