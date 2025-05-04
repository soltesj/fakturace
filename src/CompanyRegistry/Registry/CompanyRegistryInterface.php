<?php

namespace App\CompanyRegistry\Registry;

use App\CompanyRegistry\CompanyDTO;
use App\Entity\Country;

interface CompanyRegistryInterface
{
    public function support(string $countryCode): bool;

    public function fetchData(string $businessNumber): CompanyDTO;
}