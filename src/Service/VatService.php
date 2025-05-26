<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Entity\Country;
use App\Repository\VatLevelRepository;
use Doctrine\DBAL\Exception;

readonly class VatService
{
    public function __construct(private VatLevelRepository $vatLevelRepository)
    {
    }

    /**
     * @return array<int,string>
     * @throws Exception
     */
    public function getValidVatsByCompany(Company $company): array
    {
        return $this->vatLevelRepository->getValidVatByCountryPairedById($company->getCountry());
    }

    /**
     * @return array<int,string>
     * @throws Exception
     */
    public function getValidVatsByCountry(Country $country): array
    {
        return $this->vatLevelRepository->getValidVatByCountryPairedById($country);
    }
}
