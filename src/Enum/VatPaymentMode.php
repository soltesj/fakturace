<?php

namespace App\Enum;

enum VatPaymentMode: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'vat_payment_mode.monthly',
            self::QUARTERLY => 'vat_payment_mode.quarterly',
        };
    }
}
