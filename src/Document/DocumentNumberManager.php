<?php

namespace App\Document;

use App\DocumentNumber\DocumentNumberGenerator;
use App\DocumentNumber\InvalidNumberFormatException;
use App\Entity\Document;
use App\Repository\DocumentNumbersRepository;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class DocumentNumberManager
{
    public function __construct(
        private DocumentNumberGenerator $documentNumber,
        private DocumentNumbersRepository $documentNumbersRepository,
        private RequestStack $requestStack
    ) {
    }

    public function generate(Document $document): void
    {
        $year = $document->getDateIssue()->format('Y');
        $company = $document->getCompany();
        try {
            $document->setDocumentNumber(
                $this->documentNumber->generate($company, $document->getDocumentType(), $year)
            );
        } catch (InvalidNumberFormatException $e) {
            $this->requestStack->getSession()->getFlashBag()->add('danger', $e->getMessage());
            $document->setDocumentNumber('');
        }
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