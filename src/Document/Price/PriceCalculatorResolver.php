<?php

namespace App\Document\Price;

use App\Document\PriceCalculator\PriceCalculatorInterface;
use App\Enum\VatMode;

readonly class PriceCalculatorResolver
{
    public function __construct(private iterable $strategies)
    {
    }

    public function resolve(VatMode $vatMode): ?PriceCalculatorInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($vatMode)) {
                return $strategy;
            }
        }

        return null;
    }
}