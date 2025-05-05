<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\Customer;
use App\Enum\VatMode;

class VatModeService
{
    public function __construct()
    {
    }

    public function getVatMode(Company $company, Customer $customer): VatMode
    {
        $companyCountry = $company->getCountry();
        $customerCountry = $customer->getCountry();
        // Domestic
        if ($companyCountry === $customerCountry) {
            return $company->isVatPayer() ? VatMode::DOMESTIC : VatMode::NONE;
        }
        // EU -> EU
        if ($companyCountry->isEU() && $customerCountry->isEU()) {
            if ($company->isVatPayer()) {
                if ($customer->isVatPayer()) {
                    return VatMode::REVERSE_CHARGE;
                } elseif ($company->isOss()) {
                    return VatMode::OSS;
                } else {
                    return VatMode::DOMESTIC;
                }
            } elseif ($company->isOss()) {
                return VatMode::OSS;
            }
        }
        // Export mimo EU
        if (!$customerCountry->isEU()) {
            return $company->isVatPayer() ? VatMode::EXPORT : VatMode::NONE;
        }

        return VatMode::NONE;
    }

}