<?php

namespace App\Service;

use SoapClient;
use stdClass;

class CheckVatNumberValidity
{
    private const string VAT_SERVICE_WSDL = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";


    public function verifyVatNumber(string $countryCode, string $vatNumber): bool
    {
        $response = $this->executeVatCheck($countryCode, $vatNumber);

        return $response->valid;
    }

    /**
     * @return stdClass{valid: bool}
     */
    private function executeVatCheck(string $countryCode, string $vatNumber): stdClass
    {
        $client = new SoapClient(self::VAT_SERVICE_WSDL);
        $params = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
        ];

        return $client->checkVat($params);
    }
}