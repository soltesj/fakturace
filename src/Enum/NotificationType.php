<?php

namespace App\Enum;

enum NotificationType: string
{
    case BALANCE = 'balance';
    case TRANSACTION_OUTCOMING = 'transaction_outcoming';
    case TRANSACTION_INCOMING = 'transaction_incoming';


    public function label(): string
    {
        return match ($this) {
            self::TRANSACTION_OUTCOMING => 'notification_type.transaction_outcoming',
            self::TRANSACTION_INCOMING => 'notification_type.transaction_incoming',
            self::BALANCE => 'notification_type.balance',
        };
    }

    public function isTransaction(): bool
    {
        return in_array($this, [
            self::TRANSACTION_INCOMING,
            self::TRANSACTION_OUTCOMING,
        ], true);
    }
}