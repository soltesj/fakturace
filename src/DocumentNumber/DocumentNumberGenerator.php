<?php

namespace App\DocumentNumber;

use App\Entity\Company;
use App\Entity\DocumentType;
use App\Repository\DocumentNumbersRepository;

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
        $numberFormat = str_replace("YYYY", date("Y", strtotime($year)),
            $numberFormat); //echo $numberFormat."<br>";
        $numberFormat = str_replace("YY", date("y", strtotime($year)),
            $numberFormat); //echo $numberFormat."<br>";
        $numberFormat = str_replace("MM", date("m", strtotime($year)),
            $numberFormat); //echo $numberFormat."<br>";
        $numberFormat = str_replace("DD", date("d", strtotime($year)),
            $numberFormat); //echo $numberFormat."<br>";
        preg_match("([0]+n$)", $numberFormat, $matches);
        $numberChar = strlen($matches[0]); //echo $numberChar."<br>";

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