<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Service\CompanyTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CustomerController extends AbstractController
{
    use CompanyTrait;

    public function __construct(
        private readonly CustomerRepository $customerRepository
    ) {
    }

    #[Route('/{company}/customer/', name: 'app_customer_index', methods: ['GET'])]
    public function index(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $this->getCorrectCompanyUrl($request, $user);
        }
        $customers = $this->customerRepository->findByCompany($company);

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'company' => $company,
        ]);
    }

    #[Route('/{company}/customer/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $this->getCorrectCompanyUrl($request, $user);
        }
        $customer = new Customer();
        $customer->setCompany($company);
        $customer->setCountry($company->getCountry());
        $customer->setDisplay(true);
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute('app_customer_index', ['company'=>$company->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{company}/customer/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $this->getCorrectCompanyUrl($request, $user);
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute('app_customer_index', ['company'=>$company->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{company}/customer/{id}/delete', name: 'app_customer_delete', methods: ['GET'])]
    public function delete(Request $request, Company $company, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $this->getCorrectCompanyUrl($request, $user);
        }
        $customer->setDisplay(false);
        $entityManager->flush();
        $this->addFlash('info', "Zakaznik {$customer->getName()} byl odstranen");

        return $this->redirectToRoute('app_customer_index', ['company'=>$company->getId()], Response::HTTP_SEE_OTHER);
    }
}
