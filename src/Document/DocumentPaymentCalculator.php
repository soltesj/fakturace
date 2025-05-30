<?php

namespace App\Document;

use App\Enum\PaymentType;

class DocumentPaymentCalculator
{
    public function calculateNetTotal(float $currentTotal, array $newPayments): float
    {
        $netTotal = $currentTotal;
        foreach ($newPayments as $payment) {
            $netTotal += ($payment->getType() === PaymentType::INCOME ? 1 : -1) * $payment->getAmount();
        }

        return $netTotal;
    }
}