<?php

namespace App\Document;

use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Document;
use App\Repository\DocumentNumbersRepository;

readonly class DocumentNumberManager
{
    public function __construct(
        private DocumentNumberGenerator $documentNumber,
        private DocumentNumbersRepository $documentNumbersRepository,
    ) {
    }

    public function generate(Document $document): void
    {
        $year = $document->getDateIssue()->format('Y');
        $company = $document->getCompany();
        $document->setDocumentNumber(
            $this->documentNumber->generate($company, $document->getDocumentType(), $year)
        );
        $documentNumberFormat = $this->documentNumbersRepository->findOneByCompanyDocumentTypeYear(
            $company,
            $document->getDocumentType(),
            (int)$year
        );
        if ($document->getVariableSymbol() === null) {
            $document->setVariableSymbol($document->getDocumentNumber());
        }
        $documentNumberFormat->setNextNumber($documentNumberFormat->getNextNumber() + 1);
    }
}