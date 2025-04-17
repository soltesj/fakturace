<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Company\CompanyService;
use App\Service\AuthorizationService;

#[IsGranted('ROLE_USER')]
class CompanyController extends AbstractController
{

    public function __construct(
        private readonly CompanyService $companyService,
        private readonly AuthorizationService $authorizationService,
    ) {
    }

    #[Route('/{_locale}/{company}/company/', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $redirect = $this->authorizationService->checkUserCompanyAccess($request, $user, $company);
        if ($redirect) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $redirect;
        }
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'CHANGES_HAVE_BEEN_SAVED');

            return $this->redirectToRoute('app_company_edit', ['company' => $company->getId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
