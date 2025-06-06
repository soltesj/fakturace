<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Company;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use App\Repository\StatusRepository;
use App\Service\InboxIdentifierGenerator;
use App\Status\StatusValues;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class BankAccountController extends AbstractController
{
    public function __construct(
        private readonly StatusRepository $statusRepository,
        private InboxIdentifierGenerator $inboxIdentifierGenerator,
    ) {
    }

    #[Route('/{_locale}/{company}/bank-account/', name: 'app_setting_account_index', methods: ['GET'])]
    public function index(
        Company $company,
        BankAccountRepository $accountRepository
    ): Response {
        $bankAccounts = $accountRepository->findByCompany($company);
        if ($company->getIdentifiers()->count() === 0) {
            $this->inboxIdentifierGenerator->generateForCompany($company);
        }

        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $bankAccounts,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/new', name: 'app_setting_account_new', methods: ['GET', 'POST'])]
    #[IsGranted('CREATE')]
    public function new(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $bankAccount = new BankAccount($company);
        $bankAccount->setCompany($company);
        $bankAccount->setStatus($this->statusRepository->find(StatusValues::STATUS_ACTIVE));
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bankAccount);
            $entityManager->flush();
            $this->addFlash('success', 'message.changes_have_been_saved');

            return $this->redirectToRoute(
                'app_setting_account_index',
                ['company' => $company->getPublicId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('bank_account/new.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/{bankAccount}/edit', name: 'app_setting_account_edit', methods: [
        'GET',
        'POST',
    ])]
    #[IsGranted('EDIT', subject: 'bankAccount')]
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
            $this->addFlash('success', 'message.changes_have_been_saved');

            return $this->redirectToRoute(
                'app_setting_account_index',
                ['company' => $company->getPublicId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{company}/bank-account/{bankAccount}/delete', name: 'app_setting_account_delete', methods: ['GET'])]
    #[IsGranted('DELETE', subject: 'bankAccount')]
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
        $this->addFlash('success', 'message.changes_have_been_saved');

        return $this->redirectToRoute(
            'app_setting_account_index',
            ['company' => $company->getPublicId()],
            Response::HTTP_SEE_OTHER
        );
    }
}
