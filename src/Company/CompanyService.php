<?php

namespace App\Company;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CompanyService
{

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function getCorrectCompanyUrl(Request $request, User $user): string
    {
        $route = $request->get('_route');
        $routeParams = $request->get('_route_params');
        $routeParams['company'] = $user->getCompanies()->first()->getId();

        return $this->urlGenerator->generate($route, $routeParams);
        //return new RedirectResponse($this->urlGenerator->generate($route,$routeParams), 302);
    }
}
