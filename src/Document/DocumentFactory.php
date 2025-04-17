<?php

declare(strict_types=1);

namespace App\Document;

use App\Document\Types as DocumentType;
use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Entity\User;
use App\Repository\DocumentTypeRepository;
use DateTime;

class DocumentFactory
{
    public function __construct(
        private DocumentTypeRepository $documentTypeRepository,
        private readonly DocumentNumberGenerator $documentNumber,
    ) {}

    public function createInvoiceOutgoing(Company $company, User $user): Document
    {
        $now = new DateTime();
        $documentType = $this->documentTypeRepository->find(DocumentType::INVOICE_OUTGOING);
        $documentNumber = $this->documentNumber->generate($company, $documentType, $now->format('Y'));
        $documentItem = new DocumentItem();

        $document = new Document($company);
        $document->setDocumentType($documentType);
        $document->setDateIssue($now);
        $document->setDateTaxable($now);
        $document->setDateDue(new DateTime('+14 days'));
        $document->setDocumentNumber($documentNumber);
        $document->addDocumentItem(new DocumentItem());
        $document->setUser($user);
        $document->setDescription('Fakturujeme Vám služby dle Vaší objednávky:');

        // $document->addDocumentItem($documentItem);

        return $document;
    }
}