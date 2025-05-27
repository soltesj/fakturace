<?php

namespace App\Enum;

enum PaymentType: string
{
    case EXPENSE = 'payment_outcoming';
    case INCOME = 'payment_incoming';


    public function label(): string
    {
        return match ($this) {
            self::EXPENSE => 'notification_type.transaction_outcoming',
            self::INCOME => 'notification_type.transaction_incoming',
        };
    }
}