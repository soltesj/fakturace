<?php

namespace App\Document;

use App\Document\Price\PriceCalculatorService;
use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Document;
use App\Entity\DocumentPrice;
use App\Entity\VatLevel;
use App\Enum\VatMode;
use App\Repository\DocumentPriceTypeRepository;
use App\Service\VatModeService;
use Doctrine\ORM\EntityManagerInterface;

readonly class DocumentNewSaver
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DocumentNumberManager $documentNumberManager,
        private PriceCalculatorService $priceCalculatorService,
        private DocumentPriceTypeRepository $documentPriceTypeRepository,
    ) {
    }

    public function save(Document $document): void
    {
        $this->documentNumberManager->generate($document);
        [$vatPrices, $priceTotal] = $this->priceCalculatorService->calculate($document);
        $this->createDocumentPrices($document, $vatPrices, $priceTotal);
        $this->entityManager->persist($document);
        $this->entityManager->flush();
    }

    /**
     * @param array<int,array{vat:VatLevel, amount:float, vatAmount:float|null}> $vatPrices
     */
    private function createDocumentPrices(Document $document, array $vatPrices, float $priceTotal): void
    {
        foreach ($vatPrices as $vatPrice) {
            $documentPricePartial = new DocumentPrice();
            $documentPricePartial->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::PARTIAL_PRICE));
            $documentPricePartial->setVatLevel($vatPrice['vat']);
            $documentPricePartial->setAmount((string)$vatPrice['amount']);
            $documentPricePartial->setVatAmount($vatPrice['vatAmount'] ? (string)$vatPrice['vatAmount'] : null);
            $document->addDocumentPrice($documentPricePartial);
        }
        $documentPriceTotal = new DocumentPrice();
        $documentPriceTotal->setPriceType($this->documentPriceTypeRepository->find(PriceTypes::TOTAL_PRICE));
        $documentPriceTotal->setAmount((string)$priceTotal);
        $document->addDocumentPrice($documentPriceTotal);
    }
}