<?php

namespace App\DocumentNumber;

use App\Entity\Company;
use App\Entity\DocumentType;
use App\Repository\DocumentNumbersRepository;
use Exception;

class DocumentNumberGenerator
{
    public function __construct(private readonly DocumentNumbersRepository $documentNumbersRepository)
    {
    }

    public function generate(Company $company, DocumentType $documentType, string $year): string
    {
        $documentNumber = $this->documentNumbersRepository->findOneByCompanyDocumentTypeYear(
            $company,
            $documentType,
            (int)$year
        );
        $numberFormat = $documentNumber->getNumberFormat();
        $nextNumber = $documentNumber->getNextNumber();
        $nextNumber++;
        if (!$timestamp = strtotime($year)) {
            $timestamp = null;
        }
        $numberFormat = str_replace("YYYY", date("Y", $timestamp), $numberFormat);
        $numberFormat = str_replace("YY", date("y", $timestamp), $numberFormat);
        $numberFormat = str_replace("MM", date("m", $timestamp), $numberFormat);
        $numberFormat = str_replace("DD", date("d", $timestamp), $numberFormat);
        preg_match("([0]+n$)", $numberFormat, $matches);
        if (empty($matches[0])) {
            throw new InvalidNumberFormatException("INVALID_DOCUMENT_NUMBER_FORMAT");
        }
        $numberChar = strlen($matches[0]);

        return preg_replace("([0]+n$)", sprintf("%0".$numberChar."d", $nextNumber), $numberFormat);
    }

    /**
     * @param array<DocumentType|int> $documentTypes
     */
    public function exist(Company $company, array $documentTypes, int $year): bool
    {
        $documentNumbers = $this->documentNumbersRepository
            ->findByCompanyDocumentTypeYear($company, $documentTypes, $year);

        return count($documentNumbers) === count($documentTypes);
    }
}