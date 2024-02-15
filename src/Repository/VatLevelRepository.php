<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\VatLevel;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VatLevel>
 *
 * @method VatLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method VatLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method VatLevel[]    findAll()
 * @method VatLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VatLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VatLevel::class);
    }

    public function getValidVatByCountry(Country $country)
    {
        return $this->createQueryBuilder('vat_level')
            ->andWhere('vat_level.country = :country')
            ->setParameter('country', $country)
            ->andWhere('vat_level.validTo is null or vat_level.validTo >= :now')
            ->setParameter('now', new DateTime())
            ->orderBy('vat_level.vatAmount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getValidVatByCountryPairedById(Country $country)
    {
        $dbal = $this->getEntityManager()->getConnection();

        $query = $this->createQueryBuilder('vat_level')
            ->select('vat_level.id','vat_level.vatAmount')
            ->andWhere('vat_level.country = :country')
            ->andWhere('vat_level.validTo is null or vat_level.validTo >= :now')
            ->orderBy('vat_level.vatAmount', 'DESC')
            ->getQuery();


        return $dbal->executeQuery($query->getSQL(),[$country->getId(),(new DateTime())->format('Y-m-d')])
            ->fetchAllKeyValue();
    }
}
