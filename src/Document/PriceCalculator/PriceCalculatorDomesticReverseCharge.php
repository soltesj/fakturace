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
            $priceTotal += $amount;
        }

        return [$vatPrices, $priceTotal];
    }

    public function support(VatMode $vatMode): bool
    {
        return self::VAT_MODE === $vatMode;
    }
}