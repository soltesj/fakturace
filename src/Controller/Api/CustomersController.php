<?php

namespace App\Controller\Api;

use App\Entity\Company;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CustomersController extends AbstractController
{

    public function __construct(
        private readonly CustomerRepository $customerRepository,
    ) {
    }

    #[Route('/api/{company}/customers/{query}', name: 'api_customers_lookup')]
    public function lookup(Company $company, string $query = ''): Response
    {
        $customers = $this->customerRepository->search($company, $query);

        return $this->render('document/_customer.html.twig', ['customers' => $customers]);
    }
}