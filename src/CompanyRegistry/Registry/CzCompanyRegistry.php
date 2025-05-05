<?php

namespace App\CompanyRegistry\Registry;

use App\CompanyRegistry\CompanyDTO;
use App\Entity\Country;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CzCompanyRegistry implements CompanyRegistryInterface
{
    const string ARES_COMPANY_INFO_URL = "https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/%s";
    const string COUNTRY_CODE = 'CZ';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function support(string $countryCode): bool
    {
        return strtoupper($countryCode) === self::COUNTRY_CODE;
    }

    public function fetchData(string $businessNumber): CompanyDTO
    {
        $url = sprintf(self::ARES_COMPANY_INFO_URL, $businessNumber);
        $response = $this->client->request('GET', $url);
        $data = $response->toArray();

        return $this->getCompanyDTO($data);
    }

    public function getCompanyDTO(array $data): CompanyDTO
    {
        $sidlo = $data['sidlo'];
        $houseNumber = $this->mekeHouseNumber($sidlo);
        $town = $this->makeTownName($sidlo);
        $isVatPayer = $this->isVatPayer($data);
        $name = $data['obchodniJmeno'] ?? '';
        $vatNumber = $data['dic'] ?? '';
        $street = $sidlo['nazevUlice'] ?? '';
        $companyDTO = new CompanyDTO($name, $vatNumber, $street, $houseNumber, $town,
            $sidlo['psc'], $isVatPayer);

        return $companyDTO;
    }


    public function mekeHouseNumber(mixed $sidlo): string
    {
        $cisloDomovni = $sidlo['cisloDomovni'] ?? null;
        $cisloOrientacni = $sidlo['cisloOrientacni'] ?? null;
        $cisloOrientacniPismeno = $sidlo['cisloOrientacniPismeno'] ?? '';
        $houseNumber = $cisloDomovni ?? '';
        if ($cisloOrientacni) {
            $houseNumber .= "/{$cisloOrientacni}{$cisloOrientacniPismeno}";
        }

        return $houseNumber;
    }

    public function makeTownName(mixed $sidlo): string
    {
        return $sidlo['nazevMestskeCastiObvodu'] ?? $sidlo['nazevObce'] ?? '';
    }

    public function isVatPayer($data): bool
    {
        return $data['seznamRegistraci']['stavZdrojeDph'] === 'AKTIVNI';
    }
}