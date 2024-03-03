<?php

namespace App\Document;

class Types
{
    public const INVOICE_OUTGOING = 1;
    public const INVOICE_INCOMING = 2;
    public const INVOICE_OUTGOING_ADVANCE = 3;
    public const INVOICE_INCOMING_ADVANCE = 4;
    public const BANK_INCOME = 5;
    public const BANK_OUTCOME = 6;
    public const CASH_INCOME = 7;
    public const CASH_OUTCOME = 8;
    public const INVOICE_OUTGOING_TYPES = [self::INVOICE_OUTGOING, self::INVOICE_OUTGOING_ADVANCE];
    public const INVOICE_INCOMING_TYPES = [self::INVOICE_INCOMING, self::INVOICE_INCOMING_ADVANCE];

    public const TYPE_MAP = [
        self::INVOICE_OUTGOING => [self::BANK_INCOME, self::CASH_INCOME],
        self::INVOICE_OUTGOING_ADVANCE => [self::BANK_INCOME, self::CASH_INCOME],
        self::INVOICE_INCOMING => [self::BANK_OUTCOME, self::CASH_OUTCOME],
        self::INVOICE_INCOMING_ADVANCE => [self::BANK_OUTCOME, self::CASH_OUTCOME],
    ];
}