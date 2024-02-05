<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Form\BankAccountType;
use App\Repository\BankAccountRepository;
use App\Repository\CompanyRepository;
use App\Service\CompanyTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bank-account')]
class BankAccountController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private RequestStack $requestStack,
        private CompanyRepository $companyRepository,
    ) {
        $this->session = $requestStack->getSession();
    }

    #[Route('/', name: 'app_bank_account_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, BankAccountRepository $accountRepository): Response
    {
        $company = $this->getCompany();
        $bankAccounts = $accountRepository->findByCompany($company);

        return $this->render('bank_account/index.html.twig', [
            'bank_accounts' => $bankAccounts,
            'company' => $company,
        ]);
    }

    #[Route('/new', name: 'app_bank_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        $bankAccount = new BankAccount();
        $bankAccount->setCompany($company);
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bankAccount);
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank_account/new.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bank_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BankAccount $bankAccount, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        if ($bankAccount->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_bank_account_index');
        }
        $form = $this->createForm(BankAccountType::class, $bankAccount);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank_account/edit.html.twig', [
            'bank_account' => $bankAccount,
            'form' => $form,
            'company' => $company,
        ]);
    }

    #[Route('/{id}', name: 'app_bank_account_delete', methods: ['GET'])]
    public function delete(Request $request, BankAccount $bankAccount, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        if ($bankAccount->getCompany() !== $company) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');

            return $this->redirectToRoute('app_bank_account_index');
        }
        $entityManager->remove($bankAccount);
        $entityManager->flush();
        $this->addFlash('info', 'Zmeny byli ulozeny');

        return $this->redirectToRoute('app_bank_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
