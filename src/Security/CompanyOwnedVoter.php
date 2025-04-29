<?php

namespace App\Security;

use App\Entity\CompanyOwnedInterface;
use App\Entity\User;
use App\Service\AuthorizationService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyOwnedVoter extends Voter
{
    public const string VIEW = 'VIEW';
    public const string EDIT = 'EDIT';
    public const string DELETE = 'DELETE';
    public const string CREATE = 'CREATE';
    public const array ALL = [self::VIEW, self::EDIT, self::DELETE, self::CREATE];

    public function __construct(private AuthorizationService $authorizationService)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ALL)
            && ($subject instanceof CompanyOwnedInterface || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        if ($user->hasRole('ROLE_SUPER_ADMIN') || $user->hasRole('ROLE_ADMIN')) {
            return true;
        }
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
            case self::VIEW:
            case self::EDIT:
            case self::DELETE:
                if (!$subject instanceof CompanyOwnedInterface) {
                    return false;
                }

                return $this->authorizationService->checkUserCompanyAccess($user, $subject->getCompany())
                    && $this->hasRolePermission($user, $attribute);
        }

        return false;
    }

    private function canCreate(User $user): bool
    {
        return $user->hasRole('ROLE_COMPANY_EDITOR') || $user->hasRole('ROLE_COMPANY_ADMIN');
    }

    private function hasRolePermission(User $user, string $action): bool
    {
        return match ($action) {
            self::VIEW => $user->hasRole('ROLE_USER') || $user->hasRole('ROLE_COMPANY_EDITOR') || $user->hasRole('ROLE_COMPANY_ADMIN'),
            self::EDIT => $user->hasRole('ROLE_COMPANY_EDITOR') || $user->hasRole('ROLE_COMPANY_ADMIN'),
            self::DELETE => $user->hasRole('ROLE_COMPANY_ADMIN'),
            default => false,
        };
    }
}