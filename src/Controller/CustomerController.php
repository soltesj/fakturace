<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use App\Service\CompanyTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/customer')]
class CustomerController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private RequestStack $requestStack,
        private CompanyRepository $companyRepository,
        private CustomerRepository $customerRepository
    ) {
        $this->session = $requestStack->getSession();
    }

    #[Route('/', name: 'app_customer_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        $customers = $this->customerRepository->findByCompany($company);

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'company' => $company,
        ]);
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        $customer = new Customer();
        $customer->setCompany($company);
        $customer->setCountry($company->getCountry());
        $customer->setDisplay(true);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        if ($customer->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_customer_index');
        }
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_customer_delete', methods: ['GET'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        if ($customer->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_customer_index');
        }
        $customer->setDisplay(false);
        $entityManager->flush();
        $this->addFlash('info', "Zakaznik {$customer->getName()} byl odstranen");

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
