<?php

namespace App\Security;

use Symfony\Component\Yaml\Yaml;

class RoleRegistry
{
    private array $roles;

    public function __construct(string $rolesFilePath)
    {
        $config = Yaml::parseFile($rolesFilePath);
        $this->roles = $config['roles'] ?? [];
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getLabel(string $role): ?string
    {
        return $this->roles[$role] ?? null;
    }

    public function isValidRole(string $role): bool
    {
        return isset($this->roles[$role]);
    }
}