<?php

namespace App\Twig\Runtime;

use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class DateRuntime implements RuntimeExtensionInterface
{
    private string $locale;

    public function __construct(RequestStack $requestStack)
    {
        $this->locale = $requestStack->getCurrentRequest()?->getLocale() ?? 'cs';
    }

    public function diffForHumans($date): ?string
    {
        if (!$date) {
            return null;
        }
        Carbon::setLocale($this->locale);
        try {
            return Carbon::parse($date)->diffForHumans();
        } catch (Exception $e) {
            return null;
        }
    }
}
