<?php

namespace App\Repository;

use App\Document\DocumentToPay;
use App\Document\Types;
use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Document;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
     * @param int[] $documentType
     * @return ?Document[] Returns an array of Document objects
     */
    public function findByCompany(
        Company $company,
        array $documentType = [],
        string $order = 'DESC',
    ): ?array {
        $qb = $this->createQueryBuilder('document')
            ->addSelect('customer')
            ->innerJoin('document.customer', 'customer')
            ->andWhere('document.company = :company')
            ->setParameter('company', $company)
            ->andWhere('document.documentType in (:documentType)')
            ->setParameter('documentType', $documentType)
            ->orderBy('document.dateIssue', $order)
            ->orderBy('document.id', $order);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array<int> $documentTypes
     * @return DocumentToPay[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function list(
        Company $company,
        array $documentTypes,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        ?string $query = null,
        ?Customer $customer = null,
        ?string $state = null
    ): array {
        $documents = [];
        $incomeOutcomeTypes = Types::TYPE_MAP[$documentTypes[0]];
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT
                   document.id,
                   document.document_number AS documentNumber,
                   customer.name AS customerName,
                   document.date_issue AS dateIssue,
                   document.date_due AS dateDue,
                   document.price_no_vat as price,
                   document.price_total as priceWithVat,
                   (document.price_total - (SELECT COALESCE(SUM(d.price_total),0) 
                                            FROM document AS d 
                                            WHERE 
                                                d.document_id = document.id 
                                              AND d.document_type_id in ('.mb_substr(str_repeat('?,',
                count($incomeOutcomeTypes)), 0, -1).')
                                            
                                            )) as toPay
            FROM document
                     LEFT JOIN customer on document.customer_id = customer.id
            WHERE document.company_id = ?
            AND document.document_type_id in ('.mb_substr(str_repeat('?,', count($documentTypes)), 0, -1).')';
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
            $valIndex++;
        }
        //dd($stmt);
        $result = $stmt->executeQuery();
//        dd($result);
        foreach ($result->fetchAllAssociative() as $document) {
            $documents[] = new DocumentToPay(...$document);
        }

        return $documents;
    }


//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
