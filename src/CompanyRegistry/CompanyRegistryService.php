<?php

namespace App\CompanyRegistry;

use App\CompanyRegistry\Registry\CompanyRegistryInterface;
use App\Entity\Country;
use InvalidArgumentException;

class CompanyRegistryService
{

    public function __construct(private CompanyRegistryResolver $companyRegistryResolver)
    {
    }

    public function lookup(Country $country, string $businessNumber): CompanyDTO
    {
        $resolver = $this->companyRegistryResolver->resolve($businessNumber, $country->getSname());
        dump($resolver);
        if (!$resolver instanceof CompanyRegistryInterface) {
            throw new InvalidArgumentException("Pro tuto zemi zatim nemame data: {$country->getName()}");
        }

        return $resolver->fetchData($businessNumber);
    }
}