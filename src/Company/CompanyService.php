<?php

namespace App\Company;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class CompanyService
{

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function getCorrectCompanyUrl(Request $request, UserInterface $user): string
    {
        $route = $request->get('_route');
        $routeParams = $request->get('_route_params');
        if ($user->getCompanies()->first()) {
            $routeParams['company'] = $user->getCompanies()->first()->getPublicId();

            return $this->urlGenerator->generate($route, $routeParams);
        }

        return $this->urlGenerator->generate('app_home', ['_locale' => $routeParams['_locale']]);
    }
}
