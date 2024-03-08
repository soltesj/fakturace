<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

trait CompanyTrait
{
    public function getCorrectCompanyUrl(Request $request, User $user): RedirectResponse
    {

        $routeParams = $request->get('_route_params');
        $routeParams['company'] = $user->getCompanies()[0]->getId();

        return $this->redirectToRoute($request->get('_route'), $routeParams);
    }
}