<?php

namespace App\Repository;

use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Company;
use App\Entity\Document;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * @return Document[]
     */
    public function filtered(
        Company $company,
        array $documentTypes,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        ?string $query = null,
        ?string $state = null
    ): array
    {
        $qb = $this->createQueryBuilder('document')
            ->select('PARTIAL document.{id,
                documentNumber,
                dateDue,
                dateIssue,
                totalAmount,
                remainingAmount}', 'PARTIAL customer.{id, name, email}')
            ->leftJoin('document.customer', 'customer')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.documentType in (:documentTypes)')
            ->setParameter('documentTypes', $documentTypes);
        if ($dateFrom != null) {
            $qb->andWhere('document.dateDue >= :dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }
        if ($dateTo != null) {
            $qb->andWhere('document.dateDue <= :dateTo')
                ->setParameter('dateTo', $dateTo);
        }
        if ($query != null) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('document.note', ':search'),
                    $qb->expr()->like('document.documentNumber', ':search'),
                    $qb->expr()->like('customer.name', ':search'),
                    $qb->expr()->like('document.description', ':search'),
                    $qb->expr()->like('customer.name', ':search'),
                    $qb->expr()->like('customer.companyNumber', ':search'),
                    $qb->expr()->like('customer.vatNumber', ':search'),
                )
            )
                ->setParameter('search', '%'.$query.'%');
        }
        switch ($state) {
            case 'PAID':
                $qb->andWhere('document.remainingAmount = 0');
                break;
            case 'OVERDUE':
                $qb->andWhere('document.remainingAmount > 0')
                    ->andWhere('document.dateDue < :dateDue')
                    ->setParameter('dateDue', new DateTime());
                break;
            case 'ALL':
                break;
            default:
                $qb->andWhere('document.remainingAmount > 0');
                break;
        }
        $qb->orderBy('document.dateDue', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return string
     */
    public function getChartSql(): string
    {
        return <<<SQL
WITH RECURSIVE months AS (
    SELECT
        DATE(:from) AS start_date,
        DATE(:fromNext) AS end_date,
        DATE_FORMAT(:from, '%Y-%m') AS month
    UNION ALL
    SELECT
        DATE_ADD(start_date, INTERVAL 1 MONTH),
        DATE_ADD(end_date, INTERVAL 1 MONTH),
        DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y-%m')
    FROM months
    WHERE start_date < :to
)

SELECT
    m.month,
    COALESCE(SUM(p.amount), 0) AS total_income
FROM months m
         LEFT JOIN document d
                   ON d.date_issue >= m.start_date AND d.date_issue < m.end_date
                       AND d.company_id = :company_id
         LEFT JOIN document_price p
                   ON p.document_id = d.id AND p.price_type_id = :price_type
GROUP BY m.month
ORDER BY m.month;
SQL;
    }

    /**
     * @return array{labels: array<string>, data: array<float>} Array with labels and data for chart
     * @throws Exception When database query fails
     */
    public function getChart(
        Company $company,
        DateTime $dateFrom,
        DateTime $dateTo
    ): array {
        $sql = $this->getChartSql();
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('from', $dateFrom->format('Y-m-d'));
        $stmt->bindValue('fromNext', $dateFrom->modify('+1 month ')->format('Y-m-d'));
        $stmt->bindValue('to', $dateTo->format('Y-m-d'));
        $stmt->bindValue('company_id', $company->getId(), ParameterType::INTEGER);
        $stmt->bindValue('price_type', PriceTypes::TOTAL_PRICE, ParameterType::INTEGER);
        $result = $stmt->executeQuery();
        $keyValue = $result->fetchAllKeyValue();

        return [
            'labels' => array_keys($keyValue),
            'data' => array_map('floatval', array_values($keyValue)),
        ];
    }


    public function findByCompanyAndVariableSymbolAndSpecificSymbol(
        Company $company,
        string $variableSymbol,
        ?string $specificSymbol = null,
        ?string $constantSymbol = null,
    ): ?Document {
        $qb = $this->createQueryBuilder('document')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.variableSymbol = :variableSymbol')
            ->setParameter('variableSymbol', $variableSymbol);
        if ($specificSymbol) {
            $qb->andWhere('document.specificSymbol = :specificSymbol')
                ->setParameter('specificSymbol', $specificSymbol);
        }
        if ($constantSymbol) {
            $qb->andWhere('document.constantSymbol = :constantSymbol')
                ->setParameter('constantSymbol', $constantSymbol);
        }

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneById(int $id): Document
    {
        return $this->createQueryBuilder('document')
            ->andWhere('document.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getSingleResult();
    }


    /**
     * @return array<int, array{currency: string, vatTotal: string}
     */
    public function getVatToPay(Company $company, DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo): array
    {
        return $this->createQueryBuilder('document')
            ->select('curr.symbol AS currency, SUM(document_prices.vatAmount) AS vatTotal')
            ->leftJoin('document.documentPrices', 'document_prices')
            ->leftJoin('document.currency', 'curr') // <-- JOIN na entitu Currency
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.dateTaxable >= :dateFrom')
            ->setParameter('dateFrom', $dateFrom)
            ->andWhere('document.dateTaxable <= :dateTo')
            ->setParameter('dateTo', $dateTo)
            ->groupBy('curr.code')
            ->getQuery()
            ->getResult();
    }
}
