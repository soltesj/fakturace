<?php

namespace App\Service;

use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Company;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class AuthorizationService
{
    public function checkUserCompanyAccess(
        UserInterface $user,
        Company $company
    ): bool {
        return $user->getCompanies()->contains($company);
    }

}