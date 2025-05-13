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


    /**
     * @return VatMode[]
     */
    public function getAvailableVatModes(Company $company, ?Customer $customer = null): array
    {
        $companyCountry = $company->getCountry();
        $customerCountry = $customer?->getCountry();
        $vatModes = [];
        if ($customer instanceof Customer && $customer->getCompany() === $company) {
            //IS VAT PAYER
            if ($company->isVatPayer()) {
                //DOMESTIC
                if ($companyCountry === $customerCountry) {
                    $vatModes[VatMode::DOMESTIC->label()] = VatMode::DOMESTIC->value;
                    if ($companyCountry->isEU()) {
                        $vatModes[VatMode::DOMESTIC_REVERSE_CHARGE->label()] = VatMode::DOMESTIC_REVERSE_CHARGE->value;
                    }
                } //EU -> EU
                elseif ($companyCountry->isEU() && $customerCountry->isEU()) {
                    if ($customer->isVatPayer()) {
                        $vatModes[VatMode::REVERSE_CHARGE->label()] = VatMode::REVERSE_CHARGE->value;
                        $vatModes[VatMode::DOMESTIC->label()] = VatMode::DOMESTIC->value;
                    } elseif ($company->isOss()) {
                        $vatModes[VatMode::OSS->label()] = VatMode::OSS->value;
                    } else {
                        $vatModes[VatMode::DOMESTIC->label()] = VatMode::DOMESTIC->value;
                    }
                } //EXPORT
                elseif (!$customerCountry->isEU()) {
                    $vatModes[VatMode::EXPORT->label()] = VatMode::EXPORT->value;
                }
            } else {
                if ($companyCountry->isEU() && $customerCountry->isEU() && $company->isOss() && !$customer->isVatPayer()) {
                    $vatModes[VatMode::OSS->label()] = VatMode::OSS->value;
                } else {
                    $vatModes[VatMode::NONE->label()] = VatMode::NONE->value;
                }
            }
        } else {
            if ($company->isVatPayer()) {
                $vatModes[VatMode::DOMESTIC->label()] = VatMode::DOMESTIC->value;
                if ($companyCountry->isEU()) {
                    $vatModes[VatMode::DOMESTIC_REVERSE_CHARGE->label()] = VatMode::DOMESTIC_REVERSE_CHARGE->value;
                }
                $vatModes[VatMode::REVERSE_CHARGE->label()] = VatMode::REVERSE_CHARGE->value;
                $vatModes[VatMode::OSS->label()] = VatMode::OSS->value;
                $vatModes[VatMode::EXPORT->label()] = VatMode::EXPORT->value;
            } else {
                $vatModes = [
                    VatMode::NONE->label() => VatMode::NONE->value,
                    VatMode::OSS->label() => VatMode::OSS->value,
                ];
            }
        }

        return $vatModes;
    }

    public function getDefaultVatMode(Company $company): VatMode
    {
        if ($company->isVatPayer()) {
            return VatMode::DOMESTIC;
        }

        return VatMode::NONE;
    }
}