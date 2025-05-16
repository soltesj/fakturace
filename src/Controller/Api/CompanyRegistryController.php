<?php

namespace App\Controller\Api;

use App\CompanyRegistry\CompanyRegistryService;
use App\Entity\Country;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Target;

#[IsGranted('ROLE_USER')]
class CompanyRegistryController extends AbstractController
{


    private RateLimiterFactory $rateLimiter;

    public function __construct(
        private readonly CompanyRegistryService $companyRegistryService,
        #[Target('limiter.company_registry_lookup')] RateLimiterFactory $rateLimiter
    )
    {
        $this->rateLimiter = $rateLimiter;
    }

    #[Route('/api/company-registry/{country}/{businessNumber}', name: 'api_company_registry_lookup')]
    public function lookup(Country $country, string $businessNumber): JsonResponse
    {
        $userIdentifier = $this->getUser()->getUserIdentifier();
        $limiter = $this->rateLimiter->create($userIdentifier);
        $limit = $limiter->consume();
        if (!$limit->isAccepted()) {
            $retryAfter = ceil($limit->getRetryAfter()->getTimestamp() - time());
            $headers = [
                'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
                'X-RateLimit-Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $limit->getLimit(),
            ];

            return new JsonResponse([
                'error' => 'Překročen limit požadavků, zkuste to prosím později.',
            ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
        }

        try {
            $data = $this->companyRegistryService->lookup($country, $businessNumber);

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

}