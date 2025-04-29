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

    /**
     * @param array<int> $documentTypes
     * @throws Exception
     */
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
        $conn = $this->getEntityManager()->getConnection();
        $sql = $this->getDocumentToPaySql($incomeOutcomeTypes, $documentTypes, $dateFrom, $dateTo, $customer, $query,
            $state);
        $stmt = $conn->prepare($sql);
        foreach ($incomeOutcomeTypes as $index => $incomeOutcomeType) {
            $stmt->bindValue($index + 1, $incomeOutcomeType);
        }
        $valIndex = count($incomeOutcomeTypes) + 1;
        $stmt->bindValue($valIndex, $company->getId());
        $valIndex++;
        foreach ($documentTypes as $index => $documentType) {
            $stmt->bindValue($index + $valIndex, $documentType);
        }
        $valIndex = $valIndex + count($documentTypes);
        if ($dateFrom) {
            $stmt->bindValue($valIndex, $dateFrom->format('Y-m-d'));
            $valIndex++;
        }
        if ($dateTo) {
            $stmt->bindValue($valIndex, $dateTo->format('Y-m-d'));
            $valIndex++;
        }
        if ($customer) {
            $stmt->bindValue($valIndex, $customer->getId());
            $valIndex++;
        }
        if ($query) {
            $stmt->bindValue($valIndex, '%'.$query.'%');
        }

        $result = $stmt->executeQuery();
        $data = $result->fetchAllAssociative();
        foreach ($data as &$row) {
            $row['company'] = $company;
        }

        return $data;
    }

    /**
     * @param array<int> $documentTypes
     */
    public function getDocumentToPaySql(
        $incomeOutcomeTypes,
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
                   COALESCE(customer.name,\'\') AS customerName,
                   document.date_issue AS dateIssue,
                   document.date_due AS dateDue,
                   (SELECT COALESCE(SUM(document_price.amount),0) 
                                            FROM document_price 
                                            WHERE 
                                                document_price.document_id = document.id
                                            AND document_price.price_type_id = '.PriceTypes::PARTIAL_PRICE.') as price,
                   (SELECT COALESCE(document_price.amount,0) 
                                            FROM document_price 
                                            WHERE 
                                                document_price.document_id = document.id
                                            AND document_price.price_type_id = '.PriceTypes::TOTAL_PRICE.') as priceWithVat,
                   (document_price.amount - (SELECT COALESCE(SUM(d.price_total),0) 
                                            FROM document AS d 
                                            WHERE 
                                                d.document_id = document.id 
                                              AND d.document_type_id IN ('.mb_substr(str_repeat('?,',
                count($incomeOutcomeTypes)), 0, -1).')
                                            
                                            )) AS toPay
            FROM document
                     LEFT JOIN customer ON document.customer_id = customer.id
                     LEFT JOIN document_price ON document_price.document_id = document.id AND document_price.price_type_id = '.PriceTypes::TOTAL_PRICE.'
            WHERE document.company_id = ?
            AND document.document_type_id IN ('.mb_substr(str_repeat('?,', count($documentTypes)), 0, -1).')';
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
            $sql .= ' AND document.tag like ?';
        }
        switch ($state) {
            case 'PAID':
                $sql .= ' HAVING toPay = 0';
                break;
            case 'OVERDUE':
                $sql .= ' HAVING toPay > 0 AND document.date_due < NOW()';
                break;
            case  'ALL':
                break;
            default :
                $sql .= ' HAVING toPay > 0';
                break;
        }
        $sql .= ' ORDER BY date_issue DESC, document_number DESC';

        return $sql;
    }
}
