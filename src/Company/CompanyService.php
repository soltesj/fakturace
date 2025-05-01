<?php

namespace App\Company;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class CompanyService
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function getCorrectCompanyUrl(Request $request, User $user): string
    {
        $route = $request->get('_route');
        $routeParams = $request->get('_route_params');
        if ($user->getCompanies()->first()) {
            $routeParams['company'] = $user->getCompanies()->first()->getId();

            return $this->urlGenerator->generate($route, $routeParams);
        }

        return $this->urlGenerator->generate('app_home', ['_locale' => $routeParams['_locale']]);
    }
}
