<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class LocaleRedirectListener
{
    private array $locales;
    private string $defaultLocale;
    private RouterInterface $router;

    public function __construct(RouterInterface $router, array $locales = ['en', 'cs'], string $defaultLocale = 'en')
    {
        $this->router = $router;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();
        if ($path !== '/') {
            return;
        }
        $preferredLang = $request->getPreferredLanguage($this->locales) ?? $this->defaultLocale;
        $url = $this->router->generate('app_home', ['_locale' => $preferredLang]);
        $event->setResponse(new RedirectResponse($url));
    }
}
