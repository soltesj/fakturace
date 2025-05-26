<?php

namespace App\Tests\Service;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Service\AuthorizationService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthorizationServiceTest extends TestCase
{
    /** @var MockObject&CompanyRepository */
    private CompanyRepository $companyRepository;
    private AuthorizationService $authorizationService;

    public function testUserHasAccessToCompany(): void
    {
        $companyId = 1;
        $company = $this->createMock(Company::class);
        $user = $this->createMock(User::class);
        $user->method('getCompanies')->willReturn(new ArrayCollection([$company]));
        $this->companyRepository
            ->method('find')
            ->with($companyId)
            ->willReturn($company);
        $result = $this->authorizationService->checkUserCompanyAccess($user, $companyId);
        $this->assertTrue($result);
    }

    public function testCompanyNotFound(): void
    {
        $companyId = 2;
        $user = $this->createMock(User::class);
        $user->method('getCompanies')->willReturn(new ArrayCollection());
        $this->companyRepository
            ->method('find')
            ->with($companyId)
            ->willReturn(null);
        $result = $this->authorizationService->checkUserCompanyAccess($user, $companyId);
        $this->assertFalse($result);
    }

    public function testUserDoesNotHaveAccessToCompany(): void
    {
        $companyId = 3;
        $company = $this->createMock(Company::class);
        $user = $this->createMock(User::class);
        $user->method('getCompanies')->willReturn(new ArrayCollection());
        $this->companyRepository
            ->method('find')
            ->with($companyId)
            ->willReturn($company);
        $result = $this->authorizationService->checkUserCompanyAccess($user, $companyId);
        $this->assertFalse($result);
    }

    protected function setUp(): void
    {
        $this->companyRepository = $this->createMock(CompanyRepository::class);
        $this->authorizationService = new AuthorizationService($this->companyRepository);
    }
}