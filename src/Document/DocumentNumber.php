<?php

namespace App\Document;

use App\Entity\Company;
use App\Repository\DocumentNumbersRepository;

class DocumentNumber
{
    public function __construct(private DocumentNumbersRepository $documentNumbersRepository)
    {
    }

    public function generate(Company $company, int $documentTypeId, string $year): string
    {
        $documentNumber = $this->documentNumbersRepository->findByCompany($company, $documentTypeId, (int)$year);
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
}