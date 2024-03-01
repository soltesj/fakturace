<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\CompanyRepository;
use App\Service\CompanyTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private readonly CompanyRepository $companyRepository,
    ) {
    }

    #[Route('/{company}/user', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function index(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');
            return $this->getCorrectCompanyUrl($request, $user);
        }
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_user_edit', ['company'=>$company->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/index.html.twig', [
            'form' => $form,
            'company' => $company,
        ]);
    }
}
