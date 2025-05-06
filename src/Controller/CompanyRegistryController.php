<?php

namespace App\Controller;

use App\CompanyRegistry\CompanyRegistryService;
use App\Entity\Country;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CompanyRegistryController extends AbstractController
{

    public function __construct(private CompanyRegistryService $companyRegistryService)
    {
    }

    #[Route('/api/company-registry/{country}/{businessNumber}', name: 'api_company_registry_lookup')]
    public function lookup(Country $country, string $businessNumber): JsonResponse
    {
        try {
            $data = $this->companyRegistryService->lookup($country, $businessNumber);

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

}