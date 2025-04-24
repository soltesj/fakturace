<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Company;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use App\Repository\StatusRepository;
use App\Status\StatusValues;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BankAccountController extends AbstractController
{

    public function __construct(
        private readonly StatusRepository $statusRepository,
    ) {}

    #[Route('/{_locale}/{company}/bank-account/', name: 'app_bank_account_index', methods: ['GET'])]
    public function index(
        Company $company,
        BankAccountRepository $accountRepository
    ): Response {
        $bankAccounts = $accountRepository->findByCompany($company);

        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $bankAccounts,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/new', name: 'app_bank_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $bankAccount = new BankAccount();
        $bankAccount->setCompany($company);
        $bankAccount->setStatus($this->statusRepository->find(StatusValues::STATUS_ACTIVE));
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bankAccount);
            $entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute(
                'app_bank_account_index',
                ['company' => $company->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('bank_account/new.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/{id}/edit', name: 'app_bank_account_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Company $company,
        BankAccount $bankAccount,
        EntityManagerInterface $entityManager
    ): Response {
        if (count($bankAccount->getDocuments())) {
            $bankAccountNew = clone $bankAccount;
            $bankAccountNew->setStatus($this->statusRepository->find(StatusValues::STATUS_ACTIVE));
            $bankAccount->setStatus($this->statusRepository->find(StatusValues::STATUS_ARCHIVED));
        } else {
            $bankAccountNew = $bankAccount;
        }
        $form = $this->createForm(BankAccountType::class, $bankAccountNew);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bankAccountNew);
            $entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute(
                'app_bank_account_index',
                ['company' => $company->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/{id}', name: 'app_bank_account_delete', methods: ['GET'])]
    public function delete(
        Company $company,
        BankAccount $bankAccount,
        EntityManagerInterface $entityManager
    ): Response {
        if (count($bankAccount->getDocuments())) {
            $bankAccount->setStatus($this->statusRepository->find(StatusValues::STATUS_ARCHIVED));
        } else {
            $bankAccount->setStatus($this->statusRepository->find(StatusValues::STATUS_DELETED));
        }
        $entityManager->flush();
        $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

        return $this->redirectToRoute(
            'app_bank_account_index',
            ['company' => $company->getId()],
            Response::HTTP_SEE_OTHER
        );
    }
}
