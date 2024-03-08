<?php

namespace App\Document;

use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Document;
use App\Repository\DocumentNumbersRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class DocumentManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DocumentNumberGenerator $documentNumber,
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
        $document->setDocumentNumber($this->documentNumber->generate($company, $document->getDocumentType(), $year));
        $documentNumberFormat = $this->documentNumbersRepository->findOneByCompanyDocumentTypeYear($company, $document->getDocumentType(), (int)$year);


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