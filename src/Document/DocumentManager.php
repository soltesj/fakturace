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
        $this->generateDocumentNumber($document);

        [$vatPrices, $priceTotal] = $this->calculateVatPrices($document);

        $this->createDocumentPrices($document, $vatPrices, $priceTotal);

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

    private function calculateVatPrices(Document $document): array
    {
        $vatPrices = [];
        $priceTotal = 0;

        foreach ($document->getDocumentItems() as $documentItem) {
            $vatId = $documentItem->getVat()->getId();
            if (!array_key_exists($vatId, $vatPrices)) {
                $vatPrices[$vatId] = [
                    'vat' => $documentItem->getVat(),
                    'amount' => 0,
                    'vatAmount' => 0,
                ];
            }

            $amount = $documentItem->getPrice() * $documentItem->getQuantity();
            $vatAmount = $amount * ($documentItem->getVat()->getVatAmount() / 100);
            $vatPrices[$vatId]['amount'] += $amount;
            $vatPrices[$vatId]['vatAmount'] += $vatAmount;

            $priceTotal += $amount + $vatAmount;
        }

        return [$vatPrices, $priceTotal];
    }

    private function generateDocumentNumber(Document $document): void
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

    private function createDocumentPrices(Document $document, array $vatPrices, float $priceTotal): void
    {
        foreach ($vatPrices as $vatPrice) {
            $documentPricePartial = new DocumentPrice();
            $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::PARTIAL_PRICE));
            $documentPricePartial->setVatLevel($vatPrice['vat']);
            $documentPricePartial->setAmount($vatPrice['amount']);
            $documentPricePartial->setVatAmount($vatPrice['vatAmount']);
            $document->addDocumentPrice($documentPricePartial);
        }

        $documentPriceTotal = new DocumentPrice();
        $documentPriceTotal->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::TOTAL_PRICE));
        $documentPriceTotal->setAmount($priceTotal);
        $document->addDocumentPrice($documentPriceTotal);
    }
}