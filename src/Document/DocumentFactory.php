<?php

declare(strict_types=1);

namespace App\Document;

use App\Document\Types as DocumentType;
use App\DocumentNumber\DocumentNumberGenerator;
use App\DocumentNumber\InvalidNumberFormatException;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Entity\DocumentPrice;
use App\Entity\User;
use App\Enum\VatMode;
use App\Repository\DocumentTypeRepository;
use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;

class DocumentFactory
{
    public function __construct(
        private readonly DocumentTypeRepository $documentTypeRepository,
        private readonly DocumentNumberGenerator $documentNumber,
        private readonly RequestStack $requestStack,
    ) {}

    public function createInvoiceOutgoing(Company $company, User $user): Document
    {
        $now = new DateTime();
        $documentType = $this->documentTypeRepository->find(DocumentType::INVOICE_OUTGOING);
        try {
            $documentNumber = $this->documentNumber->generate($company, $documentType, $now->format('Y'));
        } catch (InvalidNumberFormatException $e) {
            $this->requestStack->getSession()->getFlashBag()->add('danger', $e->getMessage());
            $documentNumber = '';
        }

        $document = new Document($company);
        $document->setDocumentType($documentType);
        $document->setDateIssue($now);
        $document->setDateTaxable($now);
        $document->setDateDue(new DateTime("+{$company->getMaturityDays()} days"));
        $document->setDocumentNumber($documentNumber);
        $document->addDocumentItem(new DocumentItem());
        $document->setUser($user);
        $document->setDescription('Fakturujeme Vám služby dle Vaší objednávky:');
        $document->setVatMode($company->isVatPayer() ? VatMode::DOMESTIC : VatMode::NONE);

        return $document;
    }

    public function createPaymentIngoing(
        Company $company,
        ?Document $document = null,
        ?string $documentPrice = null,
        ?User $user = null
    ): Document {
        $now = new DateTime();
        $documentType = $this->documentTypeRepository->find(DocumentType::BANK_INCOME);
        try {
            $documentNumber = $this->documentNumber->generate($company, $documentType, $now->format('Y'));
        } catch (InvalidNumberFormatException $e) {
            $this->requestStack->getSession()->getFlashBag()->add('danger', $e->getMessage());
            $documentNumber = '';
        }
        $document = new Document($company);
        $document->setDocument($document);
        $document->setDocumentType($documentType);
        $document->setDateIssue($now);
        $document->setDocumentNumber($documentNumber);
        $document->setUser($user);
        $document->addDocumentItem(new DocumentItem());
        $documentPrice = new DocumentPrice();

        return $document;
    }
}