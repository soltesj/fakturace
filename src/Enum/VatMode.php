<?php

namespace App\Enum;

enum VatMode: string
{
    case DOMESTIC = 'domestic';
    case DOMESTIC_REVERSE_CHARGE = 'domestic_reverse_charge';
    case OSS = 'oss';
    case REVERSE_CHARGE = 'reverse_charge';
    case NONE = 'none';
    case EXPORT = 'export';

    public function label(): string
    {
        return match ($this) {
            self::DOMESTIC => 'Domácí režim DPH',
            self::DOMESTIC_REVERSE_CHARGE => 'Přenesená daňová povinnost',
            self::OSS => 'OSS (One Stop Shop)',
            self::REVERSE_CHARGE => 'Reverse charge',
            self::NONE => 'Bez DPH',
            self::EXPORT => 'Export',
        };
    }
}