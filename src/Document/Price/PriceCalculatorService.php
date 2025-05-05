<?php

namespace App\Document\Price;

use App\Document\PriceCalculator\PriceCalculatorInterface;
use App\Entity\Document;
use InvalidArgumentException;

class PriceCalculatorService
{
    public function __construct(private PriceCalculatorResolver $resolver)
    {
    }

    public function calculate(Document $document): array
    {
        $resolver = $this->resolver->resolve($document->getVatMode());
        if (!$resolver instanceof PriceCalculatorInterface) {
            throw new InvalidArgumentException("Neplatny zpusob zpracovani vypoctu cen: {$document->getVatMode()->label()}, {$document->getVatMode()->name}");
        }

        return $resolver->calculate($document);
    }
}