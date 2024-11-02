<?php

namespace App\Service;

use App\Document\DocumentToPay;
use App\Entity\Company;
use App\Entity\Customer;
use App\Repository\DocumentRepository;
use DateTime;
use Doctrine\DBAL\Exception;

readonly class DocumentService
{
    public function __construct(private DocumentRepository $documentRepository)
    {
    }

    /**
     * @param array<int> $documentTypes
     * @return DocumentToPay[]
     * @throws Exception
     */
    public function getDocumentToPay(
        Company $company,
        array $documentTypes,
        ?DateTime $dateFrom = null,
        ?DateTime $dateTo = null,
        ?string $query = null,
        ?Customer $customer = null,
        ?string $state = null
    ): array {
        $documents = [];
        $documentsPayData = $this->documentRepository->list(
            $company,
            $documentTypes,
            $dateFrom,
            $dateTo,
            $query,
            $customer,
            $state);
        dump($documentsPayData);
        foreach ($documentsPayData as $documentPayData) {
            $documents[] = new DocumentToPay(...$documentPayData);
        }

        return $documents;
    }

}