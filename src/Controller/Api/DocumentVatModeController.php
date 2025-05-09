<?php

namespace App\Controller\Api;

use App\Entity\Company;
use App\Entity\Customer;
use App\Enum\VatMode;
use App\Service\VatModeService;
use App\Service\VatService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DocumentVatModeController extends AbstractController
{

    public function __construct(
        private readonly VatModeService $vatModeService,
        private readonly VatService $vatService,
    ) {
    }

    #[Route('/api/{company}/document-vat-mode/{customer}', name: 'api_company_registry_lookup')]
    public function lookup(Company $company, Customer $customer): JsonResponse
    {
        $vatMode = $this->vatModeService->getVatMode($company, $customer);
        $country = $company->getCountry();
        if ($vatMode === VatMode::OSS) {
            $country = $customer->getCountry();
        }
        $vats = $this->vatService->getValidVatsByCountry($country);
        try {
            return $this->json([
                'vatMode' => $vatMode->name,
                'vatRates' => $vats,
            ]);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

}