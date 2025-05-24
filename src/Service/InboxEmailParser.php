<?php

namespace App\Service;

class InboxEmailParser
{
    public function parseIdentifier(string $email): ?string
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        if (preg_match('/^platby\.([a-z0-9]+)@[\w.\-]+$/', $email, $matches)) {
            return $matches[1];
        }

        return null;
    }
}