<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Repository\VatLevelRepository;

class VatService
{
    public function __construct(private readonly VatLevelRepository $vatLevelRepository) {}

    public function getValidVatsByCompany(Company $company): array
    {
        return $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
    }
}
