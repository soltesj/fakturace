<?php

namespace App\Controller;

use App\Customer\CustomerService;
use App\Entity\Company;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\StatusRepository;
use App\Status\StatusValues;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CustomerController extends AbstractController
{

    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly StatusRepository $statusRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CustomerService $customerService,
    ) {
    }

    #[Route('/{_locale}/{company}/customer/', name: 'app_customer_index', methods: ['GET'])]
    public function index(Company $company): Response
    {
        $customers = $this->customerRepository->findByCompany($company);

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/customer/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    #[IsGranted('CREATE')]
    public function new(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer($company);
        $customer->setCountry($company->getCountry());
        $customer->setStatus($this->statusRepository->find(StatusValues::STATUS_ACTIVE));
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute('app_customer_index', ['company' => $company->getPublicId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/customer/{customer}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'customer')]
    public function edit(
        Request $request,
        Company $company,
        Customer $customer,
    ): Response {
        $customerNew = $this->customerService->assignCustomerStatus($customer);
        $form = $this->createForm(CustomerType::class, $customerNew);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($customerNew);
            $this->entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute('app_customer_index', ['company' => $company->getPublicId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/customer/{customer}/delete', name: 'app_customer_delete', methods: ['GET'])]
    #[IsGranted('DELETE', subject: 'customer')]
    public function delete(
        Company $company,
        Customer $customer,
        EntityManagerInterface $entityManager
    ): Response {
        if (count($customer->getDocuments())) {
            $customer->setStatus($this->statusRepository->find(StatusValues::STATUS_ARCHIVED));
        } else {
            $customer->setStatus($this->statusRepository->find(StatusValues::STATUS_DELETED));
        }
        $entityManager->flush();
        $this->addFlash('info', "Zakaznik {$customer->getName()} byl odstranen");

        return $this->redirectToRoute('app_customer_index', ['company' => $company->getPublicId()],
            Response::HTTP_SEE_OTHER);
    }
}
