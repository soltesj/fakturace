<?php

namespace App\ValueResolver;

use App\Company\AccessDeniedException;
use App\Company\NotFoundException;
use App\Entity\Company;
use App\Entity\User;
use App\Logging\UnauthorizedAccessLogger;
use App\Repository\CompanyRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Bundle\SecurityBundle\Security;

#[AsTaggedItem(index: 'company', priority: 1000)]
class CompanyValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private Security $security,
        private UnauthorizedAccessLogger $unauthorizedAccessLogger,
    ) {
    }

    /**
     * @throws NotFoundException
     * @throws AccessDeniedException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== Company::class) {
            return [];
        }
        $publicId = $request->attributes->get('company');
        if (!$publicId) {
            return [];
        }
        $company = $this->companyRepository->findOneByPublicId($publicId);
        /** @var User $user */
        $user = $this->security->getToken()?->getUser();
        if ($user && !$company) {
            $this->unauthorizedAccessLogger->logAttempt($user, $publicId, $request);
            $request->getSession()->getFlashBag()->add('error', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            throw new NotFoundException("Company not found.");
        }
        if ($user && !$user->getCompanies()->contains($company)) {
            $this->unauthorizedAccessLogger->logAttempt($user, $publicId, $request);
            $request->getSession()->getFlashBag()->add('error', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            throw new AccessDeniedException("You do not have access to this company.");
        }
        yield $company;
    }
}