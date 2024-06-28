<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Currency;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CurrencyRepository;
use App\Service\CompanyTrait;
use App\Status\StatusValues;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

#[IsGranted('ROLE_USER')]
class CompanyCurrencyController extends AbstractController
{

    use CompanyTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/{_locale}/{company}/currency', name: 'app_currency_edit', methods: ['GET', 'POST'])]
    public function currencies(Request $request, Company $company, CurrencyRepository $currencyRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        $currencies = $currencyRepository->findBy([],['currencyCode' => 'ASC']);


        return $this->render('currency/index.html.twig', [
            'company' => $company,
            'currencies' =>$currencies,
        ]);
    }

    #[Route('/{_locale}/{company}/currency/{currency}/add/', name: 'app_company_add_currency', methods: ['GET'])]
    public function add(Request $request, Currency $currency, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }

            try {
                $company->addCurrency($currency);
                $this->entityManager->flush();
                $this->addFlash('success', "Měna {$currency->getCurrencyCode()} byla přidána");
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->addFlash('warning', 'CHANGES_HAVE_BEEN_REMOVED');
            }



        return $this->redirectToRoute('app_currency_edit', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{_locale}/{company}/currency/{currency}/remove/', name: 'app_company_remove_currency', methods: ['GET'])]
    public function remove(Request $request, Currency $currency, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');

            return $this->getCorrectCompanyUrl($request, $user);
        }
        if (count($company->getCurrency()) > 1) {
            try {
                $company->removeCurrency($currency);
                $this->entityManager->flush();
                $this->addFlash('success', "Měna {$currency->getCurrencyCode()} byla odebrána");
            } catch (Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
                $this->addFlash('warning', 'CHANGES_HAVE_BEEN_REMOVED');
            }
        } else {
            $this->addFlash('warning', 'CHANGES_HAVE_BEEN_REMOVED');
        }


        return $this->redirectToRoute('app_currency_edit', ['company' => $company->getId()], Response::HTTP_SEE_OTHER);
    }
}
