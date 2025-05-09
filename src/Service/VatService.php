<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Entity\Country;
use App\Repository\VatLevelRepository;

readonly class VatService
{
    public function __construct(private VatLevelRepository $vatLevelRepository)
    {
    }

    public function getValidVatsByCompany(Company $company): array
    {
        return $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
    }

    public function getValidVatsByCountry(Country $country): array
    {
        return $this->vatLevelRepository->getValidVatByCountryPairedById($country);
    }
}
