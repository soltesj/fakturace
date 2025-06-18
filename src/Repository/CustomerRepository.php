<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Customer;
use App\Status\StatusValues;
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
    public function findByCompany(
        Company $company,
        int $statusId = StatusValues::STATUS_ACTIVE,
        string $order = 'ASC'
    ): array {
        $qb = $this->createQueryBuilder('customer')
            ->andWhere('customer.company = :company')
            ->setParameter('company', $company)
            ->andWhere('customer.status = :status')
            ->setParameter('status', $statusId);
        $qb->orderBy('customer.name', $order);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Customer[]
     */
    public function search(Company $company, string $query): array
    {
        if ($query === '') {
            return [];
        }
        $qb = $this->createQueryBuilder('customer')
            ->andWhere('customer.company = :company')
            ->setParameter('company', $company)
            ->andWhere('customer.status = :status')
            ->setParameter('status', StatusValues::STATUS_ACTIVE);
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('customer.name', ':query'),
                $qb->expr()->like('customer.companyNumber', ':query'),
                $qb->expr()->like('customer.vatNumber', ':query'),
                $qb->expr()->like('customer.contact', ':query'),
            )
        )
            ->setParameter('query', '%'.$query.'%');

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return Customer[]
     */
    public function lastCustomersByCompany(Company $company, int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.documents', 'd')
            ->where('c.company = :company')
            ->setParameter('company', $company)
            ->orderBy('d.dateIssue', 'DESC')
            ->groupBy('c.id')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
