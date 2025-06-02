<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

use function Symfony\Component\Translation\t;

#[IsGranted('ROLE_USER')]
class CompanyCurrencyController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/{_locale}/{company}/currency', name: 'app_setting_currency_edit', methods: ['GET', 'POST'])]
    public function currencies(Company $company, CurrencyRepository $currencyRepository): Response
    {
        $currencies = $currencyRepository->findBy([], ['code' => 'ASC']);

        return $this->render('currency/index.html.twig', [
            'company' => $company,
            'currencies' => $currencies,
        ]);
    }

    #[Route('/{_locale}/{company}/currency/{currency}/add/', name: 'app_company_add_currency', methods: ['GET'])]
    public function add(Currency $currency, Company $company): Response
    {
        try {
            $company->addCurrency($currency);
            $this->entityManager->flush();
            $this->addFlash('success', t('message.currency_have_been_added'));
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            $this->addFlash('warning', t('message.general_error'));
        }

        return $this->redirectToRoute('app_setting_currency_edit', ['company' => $company->getId()],
            Response::HTTP_SEE_OTHER);
    }

    #[Route('/{_locale}/{company}/currency/{currency}/remove/', name: 'app_company_remove_currency', methods: ['GET'])]
    public function remove(Currency $currency, Company $company): Response
    {
        if (count($company->getCurrency()) > 1) {
            try {
                $company->removeCurrency($currency);
                $this->entityManager->flush();
                $this->addFlash('success', t('message.currency_have_been_removed'));
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->addFlash('warning', t('message.general_error'));
            }
        } else {
            $this->addFlash('warning', t('message.general_error'));
        }

        return $this->redirectToRoute('app_setting_currency_edit', ['company' => $company->getId()],
            Response::HTTP_SEE_OTHER);
    }
}
