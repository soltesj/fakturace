<?php

namespace App\Service;

use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Company;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class AuthorizationService
{

    public function __construct(
        private CompanyRepository $companyRepository,
    ) {
    }

    public function checkUserCompanyAccess(
        UserInterface $user,
        Company|int $company
    ): bool {
        if (!$company instanceof Company) {
            $company = $this->companyRepository->find($company);
        }

        return $company !== null && $user->getCompanies()->contains($company);
    }

}