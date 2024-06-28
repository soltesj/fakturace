<?php

namespace App\Document;

use App\DocumentNumber\DocumentNumberGenerator;
use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Document;
use App\Entity\DocumentPrice;
use App\Repository\DocumentNumbersRepository;
use App\Repository\DocumentPriceTypeRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

readonly class DocumentManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DocumentNumberGenerator $documentNumber,
        private DocumentNumbersRepository $documentNumbersRepository,
        private DocumentPriceTypeRepository $documentPriceTypeRepository,
    ) {
    }

    /**
     * @throws UniqueConstraintViolationException|Throwable
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
            $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::PARTIAL_PRICE));
            $documentPricePartial->setVatLevel($vatPrice['vat']);
            $documentPricePartial->setAmount($vatPrice['amount']);
            $documentPricePartial->setVatAmount($vatPrice['vatAmount']);
            $document->addDocumentPrice($documentPricePartial);


        }
        $documentPricePartial = new DocumentPrice();
        $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::TOTAL_PRICE));
        $documentPricePartial->setAmount($priceTotal);
        $document->addDocumentPrice($documentPricePartial);

        $this->save($document);
    }

    /**
     * @throws UniqueConstraintViolationException | Throwable
     */
    public function save(Document $document): void
    {
        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->persist($document);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}