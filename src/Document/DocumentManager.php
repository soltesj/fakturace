<?php

namespace App\Document;

use App\Entity\Company;
use App\Entity\Document;
use App\Repository\DocumentNumbersRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class DocumentManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DocumentNumber $documentNumber,
        private DocumentNumbersRepository $documentNumbersRepository
    ) {
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function saveNew(Document $document): void
    {
        $year = $document->getDateIssue()->format('Y');
        $company = $document->getCompany();
        $documentTypeId = $document->getDocumentType()->getId();
        $document->setDocumentNumber($this->documentNumber->generate($company, $documentTypeId, $year));
        $documentNumberFormat = $this->documentNumbersRepository->findByCompany($company, $documentTypeId, (int)$year);


        if ($document->getVariableSymbol() === null) {
            $document->setVariableSymbol($document->getDocumentNumber());
        }
        $documentNumberFormat->setNextNumber($documentNumberFormat->getNextNumber()+1);
        $this->save($document);
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function save(Document $document): void
    {
        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->persist($document);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (UniqueConstraintViolationException $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}