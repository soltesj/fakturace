<?php

namespace App\Document\PriceCalculator;

use App\Entity\Document;
use App\Entity\VatLevel;
use App\Enum\VatMode;

class PriceCalculatorDomesticReverseCharge implements PriceCalculatorInterface
{
    const VatMode VAT_MODE = VatMode::DOMESTIC_REVERSE_CHARGE;

    /**
     * @return  array{array<int,array{vat:VatLevel, amount:float, vatAmount:float|null}>, float} $vatPrices
     */
    public function calculate(Document $document): array
    {
        $vatPrices = [];
        $priceTotal = 0;
        foreach ($document->getDocumentItems() as $documentItem) {
            $priceTotal += $documentItem->getPrice() * $documentItem->getQuantity();
        }

        return [$vatPrices, $priceTotal];
    }

    public function support(VatMode $vatMode): bool
    {
        return self::VAT_MODE === $vatMode;
    }
}