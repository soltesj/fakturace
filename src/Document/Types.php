<?php

namespace App\Document;

class Types
{
    public const int INVOICE_OUTGOING = 1;
    public const int INVOICE_INCOMING = 2;
    public const int INVOICE_OUTGOING_ADVANCE = 3;
    public const int INVOICE_INCOMING_ADVANCE = 4;
    public const int BANK_INCOME = 5;
    public const int BANK_OUTCOME = 6;
    public const int CASH_INCOME = 7;
    public const int CASH_OUTCOME = 8;
    public const array INVOICE_OUTGOING_TYPES = [self::INVOICE_OUTGOING, self::INVOICE_OUTGOING_ADVANCE];
    public const array INVOICE_INCOMING_TYPES = [self::INVOICE_INCOMING, self::INVOICE_INCOMING_ADVANCE];

    public const array TYPE_MAP = [
        self::INVOICE_OUTGOING => [self::BANK_INCOME, self::CASH_INCOME],
        self::INVOICE_OUTGOING_ADVANCE => [self::BANK_INCOME, self::CASH_INCOME],
        self::INVOICE_INCOMING => [self::BANK_OUTCOME, self::CASH_OUTCOME],
        self::INVOICE_INCOMING_ADVANCE => [self::BANK_OUTCOME, self::CASH_OUTCOME],
    ];
}