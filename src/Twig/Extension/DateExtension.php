<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\DateRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('diff_for_humans', [DateRuntime::class, 'diffForHumans']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [DateRuntime::class, 'doSomething']),
        ];
    }
}
