<?php

namespace App\Tests\Service;

use App\Entity\Company;
use App\Entity\Country;
use App\Repository\VatLevelRepository;
use App\Service\VatService;
use PHPUnit\Framework\TestCase;

class VatServiceTest extends TestCase
{
    private VatLevelRepository $vatLevelRepository;
    private VatService $vatService;

    public function testGetValidVatsByCompanyReturnsExpectedVats(): void
    {
        $country = $this->createMock(Country::class);
        $company = $this->createMock(Company::class);
        $company->expects($this->once())->method('getCountry')->willReturn($country);
        $expectedVats = [
            ['id' => 1, 'rate' => 20],
            ['id' => 2, 'rate' => 10],
        ];
        $this->vatLevelRepository
            ->expects($this->once())
            ->method('getValidVatByCountryPairedById')
            ->with($country)
            ->willReturn($expectedVats);
        $result = $this->vatService->getValidVatsByCompany($company);
        $this->assertSame($expectedVats, $result);
    }

    public function testGetValidVatsByCompanyHandlesEmptyRepositoryResponse(): void
    {
        $country = $this->createMock(Country::class);
        $company = $this->createMock(Company::class);
        $company->expects($this->once())->method('getCountry')->willReturn($country);
        $this->vatLevelRepository
            ->expects($this->once())
            ->method('getValidVatByCountryPairedById')
            ->with($country)
            ->willReturn([]);
        $result = $this->vatService->getValidVatsByCompany($company);
        $this->assertSame([], $result);
    }

    protected function setUp(): void
    {
        $this->vatLevelRepository = $this->createMock(VatLevelRepository::class);
        $this->vatService = new VatService($this->vatLevelRepository);
    }
}