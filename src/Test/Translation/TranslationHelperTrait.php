<?php

namespace App\Test\Translation;

trait TranslationHelperTrait
{
    private function t(string $key, array $params = [], ?string $domain = null): string
    {
        return self::getContainer()
            ->get('translator')
            ->trans($key, $params, $domain);
    }
}