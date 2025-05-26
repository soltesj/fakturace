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

    /**
     * @var array{
     *      ico: string,
     *      obchodniJmeno: string,
     *      sidlo: array{
     *          kodStatu: string,
     *          nazevStatu: string,
     *          kodKraje: int,
     *          nazevKraje: string,
     *          kodOkresu: int,
     *          nazevOkresu: string,
     *          kodObce: int,
     *          nazevObce: string,
     *          kodUlice: int,
     *          nazevUlice: string,
     *          cisloDomovni: int,
     *          kodCastiObce: int,
     *          nazevCastiObce: string,
     *          kodAdresnihoMista: int,
     *          psc: int,
     *          textovaAdresa: string,
     *          standardizaceAdresy: bool,
     *          typCisloDomovni: int
     *      },
     *      pravniForma: string,
     *      financniUrad: string,
     *      datumVzniku: string,
     *      datumAktualizace: string,
     *      icoId: string,
     *      adresaDorucovaci: array{
     *          radekAdresy1: string
     *      },
     *      seznamRegistraci: array{
     *          stavZdrojeVr: string,
     *          stavZdrojeRes: string,
     *          stavZdrojeRzp: string,
     *          stavZdrojeNrpzs: string,
     *          stavZdrojeRpsh: string,
     *          stavZdrojeRcns: string,
     *          stavZdrojeSzr: string,
     *          stavZdrojeDph: string,
     *          stavZdrojeSd: string,
     *          stavZdrojeIr: string,
     *          stavZdrojeCeu: string,
     *          stavZdrojeRs: string,
     *          stavZdrojeRed: string,
     *          stavZdrojeMonitor: string
     *      },
     *      primarniZdroj: string,
     *      dalsiUdaje: array<int, array{
     *          obchodniJmeno?: array<int|string, mixed>,
     *          sidlo?: array<int, array{
     *              sidlo: array{
     *                  kodStatu: string,
     *                  nazevStatu: string,
     *                  kodKraje: int,
     *                  nazevKraje: string,
     *                  kodOkresu: int,
     *                  nazevOkresu: string,
     *                  kodObce: int,
     *                  nazevObce: string,
     *                  kodUlice: int,
     *                  nazevUlice: string,
     *                  cisloDomovni: int,
     *                  kodCastiObce: int,
     *                  nazevCastiObce: string,
     *                  kodAdresnihoMista: int,
     *                  psc: int,
     *                  textovaAdresa: string,
     *                  standardizaceAdresy: bool,
     *                  typCisloDomovni: int
     *              },
     *              primarniZaznam: bool
     *          }>,
     *          pravniForma?: string,
     *          datovyZdroj?: string
     *      }>,
     *      czNace: array<int, mixed>
     *  } $data
     */
    public function getCompanyDTO(array $data): CompanyDTO
    {
        $residence = $data['sidlo'];
        $houseNumber = $this->mekeHouseNumber($residence);
        $town = $this->makeTownName($residence);
        $isVatPayer = $this->isVatPayer($data);
        $name = $data['obchodniJmeno'] ?? '';
        $vatNumber = $data['dic'] ?? '';
        $street = $residence['nazevUlice'] ?? '';
        $companyDTO = new CompanyDTO($name, $vatNumber, $street, $houseNumber, $town,
            $residence['psc'], $isVatPayer);

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