<?php

namespace App\CompanyRegistry;

use App\CompanyRegistry\Registry\CompanyRegistryInterface;

class CompanyRegistryResolver
{
    public function __construct(private readonly iterable $strategies)
    {
    }

    public function resolve(string $ico, string $countryCode): ?CompanyRegistryInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($countryCode)) {
                return $strategy;
            }
        }

        return null;
    }
}