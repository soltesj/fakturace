<?php

namespace App\Document;

use App\Document\Price\PriceCalculatorService;
use App\Document\PriceCalculator\PriceCalculatorInterface;
use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Document;
use App\Entity\DocumentPrice;
use App\Entity\DocumentPriceType;
use App\Entity\VatLevel;
use App\Service\VatModeService;
use Doctrine\ORM\EntityManagerInterface;

class DocumentUpdater
{

    /**
     * @var array<string,PriceCalculatorInterface>
     */
    private array $priceCalculators;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PriceCalculatorService $priceCalculatorService,
        private readonly VatModeService $vatModeService,
    ) {
    }

    public function update(Document $document): void
    {
        $vatMode = $this->vatModeService->getVatMode($document->getCompany(), $document->getCustomer());
        $document->setVatMode($vatMode);
        [$vatPrices, $priceTotal] = $this->priceCalculatorService->calculate($document);
        $this->updateExistingPrices($document, $vatPrices, $priceTotal);
        $this->createMissingPrices($document, $vatPrices);
        $this->entityManager->flush();
    }

    /**
     * @param array<int,array{vat:VatLevel, amount:float, vatAmount:float|null}> $vatPrices
     */
    private function updateExistingPrices(Document $document, array &$vatPrices, float $priceTotal): void
    {
        foreach ($document->getDocumentPrices() as $documentPrice) {
            $priceTypeId = $documentPrice->getPriceType()->getId();
            if ($priceTypeId === PriceTypes::PARTIAL_PRICE) {
                $vatLevelId = $documentPrice->getVatLevel()?->getId();
                if ($vatLevelId !== null && array_key_exists($vatLevelId, $vatPrices)) {
                    $data = $vatPrices[$vatLevelId];
                    $documentPrice->setAmount((string)$data['amount']);
                    unset($vatPrices[$vatLevelId]);
                } else {
                    $this->entityManager->remove($documentPrice);
                }
            }
            if ($priceTypeId === PriceTypes::TOTAL_PRICE) {
                $documentPrice->setAmount((string)$priceTotal);
            }
        }
    }

    /**
     * @param array<int,array{vat:VatLevel, amount:float, vatAmount:float|null}> $vatPrices
     */
    private function createMissingPrices(Document $document, array $vatPrices): void
    {
        foreach ($vatPrices as $data) {
            $priceEntity = $this->createPriceEntity($document, $data['vat'], $data['amount'], $data['vatAmount']);
            $this->entityManager->persist($priceEntity);
            $document->addDocumentPrice($priceEntity);
        }
    }

    public function createPriceEntity(
        Document $document,
        VatLevel $vatLevel,
        float $amount,
        float $vatAmount
    ): DocumentPrice
    {
        $priceEntity = new DocumentPrice();
        $priceEntity->setDocument($document);
        $priceEntity->setPriceType($this->entityManager->getReference(DocumentPriceType::class,
            PriceTypes::PARTIAL_PRICE));
        $priceEntity->setVatLevel($vatLevel);
        $priceEntity->setAmount(number_format($amount, 2, '.', ''));
        $priceEntity->setVatAmount(number_format($vatAmount, 2, '.', ''));

        return $priceEntity;
    }
}