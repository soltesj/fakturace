<?php

namespace App\Document;

use App\Document\Price\PriceCalculatorService;
use App\DocumentPrice\Types as PriceTypes;
use App\Entity\Document;
use App\Entity\DocumentItem;
use App\Entity\DocumentPrice;
use App\Entity\DocumentPriceType;
use App\Entity\VatLevel;
use App\Repository\CustomerRepository;
use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

readonly class DocumentUpdater
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PriceCalculatorService $priceCalculatorService,
        private PaymentRepository $paymentRepository,
        private CustomerRepository $customerRepository,
    ) {
    }

    public function update(Document $document, ArrayCollection $originalItems, int $customerId): void
    {
        $customer = $this->customerRepository->find($customerId);
        $document->setCustomer($customer);
        $this->removeUnassociatedItems($originalItems, $document);
        [$vatPrices, $priceTotal] = $this->priceCalculatorService->calculate($document);
        $this->updateExistingPrices($document, $vatPrices, $priceTotal);
        $document->setTotalAmount($priceTotal);
        $total = $this->paymentRepository->findCurrentTotalForDocument($document);
        $document->setRemainingAmount($priceTotal - $total);
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

    public function removeUnassociatedItems(ArrayCollection $originalItems, Document $document): void
    {
        /** @var DocumentItem $documentItem */
        foreach ($originalItems as $documentItem) {
            if (false === $document->getDocumentItems()->contains($documentItem)) {
                $this->entityManager->remove($documentItem);
            }
        }
    }
}