<?php

namespace App\Service;

use App\Company\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthorizationService{

    public function __construct(
        private readonly CompanyService $companyService,
    ) {}

    public function checkUserCompanyAccess(Request $request, User $user, Company $company): ?RedirectResponse
    {
        if (!$user->getCompanies()->contains($company)) {
        
            return new RedirectResponse($this->companyService->getCorrectCompanyUrl($request, $user), 302);
        }


        return null;
    }

}