<?php

namespace App\Enum;

enum VatMode: string
{
    case DOMESTIC = 'domestic';
    case OSS = 'oss';
    case REVERSE_CHARGE = 'reverse_charge';
    case NONE = 'none';

    public function label(): string
    {
        return match ($this) {
            self::DOMESTIC => 'Domácí režim DPH',
            self::OSS => 'OSS (One Stop Shop)',
            self::REVERSE_CHARGE => 'Reverse charge',
            self::NONE => 'Bez DPH',
        };
    }
}