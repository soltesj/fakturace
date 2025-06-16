<?php

namespace App\Company\Creation;

use App\Entity\Company;
use App\Entity\DocumentNumbers;
use App\Repository\DocumentTypeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

readonly class DocumentNumberInitializerOnCompanyCreation
{
    public function __construct(private DocumentTypeRepository $documentTypeRepository)
    {
    }

    public function initialize(Company $company, EntityManagerInterface $em, UnitOfWork $uow): void
    {
        $documentTypes = $this->documentTypeRepository->findAll();
        if (count($documentTypes) === 0) {
            return;
        }
        $year = (int)new DateTimeImmutable()->format('Y');
        foreach ($documentTypes as $documentType) {
            $documentNumber = new DocumentNumbers($company, $documentType, $year, $documentType->getDefaultFormat());
            $em->persist($documentNumber);
            $uow->computeChangeSet($em->getClassMetadata(DocumentNumbers::class), $documentNumber);
        }
    }
}