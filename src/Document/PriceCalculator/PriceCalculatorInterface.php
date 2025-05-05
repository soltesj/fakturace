<?php

namespace App\Document\PriceCalculator;

use App\Entity\Document;
use App\Enum\VatMode;

interface PriceCalculatorInterface
{
    public function support(VatMode $vatMode): bool;

    public function calculate(Document $document): array;
}