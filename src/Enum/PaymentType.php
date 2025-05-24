<?php

namespace App\Enum;

enum PaymentType: string
{
    case OUTCOMING = 'payment_outcoming';
    case INCOMING = 'payment_incoming';


    public function label(): string
    {
        return match ($this) {
            self::OUTCOMING => 'notification_type.transaction_outcoming',
            self::INCOMING => 'notification_type.transaction_incoming',
        };
    }
}