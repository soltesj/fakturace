<?php

namespace App\Service;

use App\Entity\Company;

trait CompanyTrait
{
    private function getCompany(): ?Company
    {
        $company = $this->session->get('company');
        if ($company === null) {
            return null;
        }

        return $this->companyRepository->find($company?->getId());
    }
}