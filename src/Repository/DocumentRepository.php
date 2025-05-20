<?php

namespace App\Repository;

use App\Document\Types;
use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Document;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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

    public function findByCompanyAndId(
        Company $company,
        int $id,
    ): Document {
        $qb = $this->createQueryBuilder('document')
            ->leftJoin('document.documentItems', 'documentItems')
            ->addSelect('documentItems')
            ->leftJoin('document.documentPrices', 'documentPrices')
            ->addSelect('documentPrices')
            ->innerJoin('document.customer', 'customer')
            ->addSelect('customer')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();
    }

    public function list(
        Company $company,
        array $documentTypes,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        ?string $query = null,
        ?Customer $customer = null,
        ?string $state = null
    ): array
    {
        $incomeOutcomeTypes = Types::TYPE_MAP[$documentTypes[0]];
        $sql = $this->getDocumentToPaySql($incomeOutcomeTypes, $documentTypes, $dateFrom, $dateTo, $customer, $query,
            $state);
        $params = $this->buildSqlParameters($incomeOutcomeTypes, $company, $documentTypes, $dateFrom, $dateTo,
            $customer, $query);
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        foreach ($params as $index => $param) {
            $stmt->bindValue($index + 1, $param);
        }
        $result = $stmt->executeQuery();
        $data = $result->fetchAllAssociative();
        foreach ($data as &$row) {
            $row['company'] = $company;
        }

        return $data;
    }

    /**
     * @return array<int|string|float>
     */
    private function buildSqlParameters(
        array $incomeOutcomeTypes,
        Company $company,
        array $documentTypes,
        ?DateTime $dateFrom,
        ?DateTime $dateTo,
        ?Customer $customer,
        ?string $query
    ): array {
        $params = [];
        foreach ($incomeOutcomeTypes as $type) {
            $params[] = $type;
        }
        $params[] = $company->getId();
        foreach ($documentTypes as $type) {
            $params[] = $type;
        }
        if ($dateFrom) {
            $params[] = $dateFrom->format('Y-m-d');
        }
        if ($dateTo) {
            $params[] = $dateTo->format('Y-m-d');
        }
        if ($customer) {
            $params[] = $customer->getId();
        }
        if ($query) {
            $like = '%'.$query.'%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return $params;
    }

    public function getDocumentToPaySql(
        array $incomeOutcomeTypes,
        array $documentTypes,
        ?DateTime $dateFrom,
        ?DateTime $dateTo,
        ?Customer $customer,
        ?string $query,
        ?string $state
    ): string {
        $sql = '
        SELECT
            document.id,
            document.document_number AS documentNumber,
            COALESCE(customer.name, \'\') AS customerName,
            document.date_issue AS dateIssue,
            document.date_due AS dateDue,
            (SELECT COALESCE(SUM(document_price.amount), 0)
             FROM document_price
             WHERE document_price.document_id = document.id
               AND document_price.price_type_id = '.PriceTypes::PARTIAL_PRICE.') AS price,
            (SELECT COALESCE(document_price.amount, 0)
             FROM document_price
             WHERE document_price.document_id = document.id
               AND document_price.price_type_id = '.PriceTypes::TOTAL_PRICE.') AS priceWithVat,
            (document_price.amount - (SELECT COALESCE(SUM(d.price_total), 0)
                                      FROM document AS d
                                      WHERE d.document_id = document.id
                                        AND d.document_type_id IN ('.rtrim(str_repeat('?,', count($incomeOutcomeTypes)),
                ',').')
                                     )) AS toPay
        FROM document
            LEFT JOIN customer ON document.customer_id = customer.id
            LEFT JOIN document_price ON document_price.document_id = document.id
                AND document_price.price_type_id = '.PriceTypes::TOTAL_PRICE.'
        WHERE document.company_id = ?
          AND document.document_type_id IN ('.rtrim(str_repeat('?,', count($documentTypes)), ',').')';
        if ($dateFrom) {
            $sql .= ' AND document.date_issue >= ?';
        }
        if ($dateTo) {
            $sql .= ' AND document.date_issue <= ?';
        }
        if ($customer) {
            $sql .= ' AND document.customer_id = ?';
        }
        if ($query) {
            $sql .= ' AND (document.note LIKE ? OR document.document_number LIKE ? OR customer.name LIKE ? OR document.description LIKE ?)';
        }
        switch ($state) {
            case 'PAID':
                $sql .= ' HAVING toPay = 0';
                break;
            case 'OVERDUE':
                $sql .= ' HAVING toPay > 0 AND document.date_due < NOW()';
                break;
            case 'ALL':
                break;
            default:
                $sql .= ' HAVING toPay > 0';
                break;
        }
        $sql .= ' ORDER BY date_issue DESC, document_number DESC';

        return $sql;
    }
}
