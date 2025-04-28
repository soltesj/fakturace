<?php

namespace App\Document;

use App\Entity\Document;

class PriceCalculator
{

    public function calculateVatPrices(Document $document): array
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
}