<?php

namespace App\Document;

class Types
{
    public const INVOICE_OUTGOING = 1;
    public const INVOICE_OUTGOING_ADVANCE = 3;
    public const INVOICE_OUTGOING_TYPES = [self::INVOICE_OUTGOING,self::INVOICE_OUTGOING_ADVANCE];
}