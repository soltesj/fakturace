<?php

namespace App\Document;

use App\DocumentNumber\DocumentNumberGenerator;
use App\Entity\Document;
use App\Entity\DocumentPrice;
use App\Repository\DocumentNumbersRepository;
use App\Repository\DocumentPriceTypeRepository;
use App\Repository\VatLevelRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class DocumentManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DocumentNumberGenerator $documentNumber,
        private readonly DocumentNumbersRepository $documentNumbersRepository,
        private readonly DocumentPriceTypeRepository $documentPriceTypeRepository,
        private readonly VatLevelRepository $vatLevelRepository
    ) {
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    public function saveNew(Document $document): void
    {
        $vatPrices =[];
        $priceTotal = 0;
        $year = $document->getDateIssue()->format('Y');
        $company = $document->getCompany();
        $document->setDocumentNumber($this->documentNumber->generate($company, $document->getDocumentType(), $year));
        $documentNumberFormat = $this->documentNumbersRepository->findOneByCompanyDocumentTypeYear($company,
            $document->getDocumentType(), (int)$year);
        if ($document->getVariableSymbol() === null) {
            $document->setVariableSymbol($document->getDocumentNumber());
        }
        $documentNumberFormat->setNextNumber($documentNumberFormat->getNextNumber() + 1);
        foreach ($document->getDocumentItems() as $documentItem) {
            if (!array_key_exists($documentItem->getVat()->getId(), $vatPrices)) {
                $vatPrices[$documentItem->getVat()->getId()] = [
                    'vat' => $documentItem->getVat(),
                    'amount' => 0,
                    'vatAmount' => 0,
                ];
            }
            $amount = $documentItem->getPrice() * $documentItem->getQuantity();
            $vatAmount = $amount * ($documentItem->getVat()->getVatAmount() / 100);
            $vatPrices[$documentItem->getVat()->getId()]['amount'] += $amount;
            $vatPrices[$documentItem->getVat()->getId()]['vatAmount'] += $vatAmount;

            $priceTotal += $amount + $vatAmount;
        }

        foreach ($vatPrices as $vatPrice) {
            $documentPricePartial = new DocumentPrice();
            $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(2));
            $documentPricePartial->setVatLevel($vatPrice['vat']);
            $documentPricePartial->setAmount($vatPrice['amount']);
            $documentPricePartial->setVatAmount($vatPrice['vatAmount']);
            $document->addDocumentPrice($documentPricePartial);


        }
        $documentPricePartial = new DocumentPrice();
        $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(1));
        $documentPricePartial->setAmount($priceTotal);
        $document->addDocumentPrice($documentPricePartial);

        dump($document);
        dump($document->getDocumentPrices());
        dump($document->getDocumentItems());
        dump($vatPrices);
//        $this->save($document);
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