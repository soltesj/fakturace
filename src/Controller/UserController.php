<?php

namespace App\Controller;

use App\Form\UserEditFormType;
use App\Repository\CompanyRepository;
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
class UserController extends AbstractController
{
    use CompanyTrait;

    private SessionInterface $session;

    public function __construct(
        private RequestStack $requestStack,
        private CompanyRepository $companyRepository,
    ) {
        $this->session = $requestStack->getSession();
    }

    #[Route('/user', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = $this->getCompany();
        $user = $this->getUser();
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('info', 'Zmeny byli ulozeny');

            return $this->redirectToRoute('app_user_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/index.html.twig', [
            'form' => $form,
            'company' => $company,
        ]);
    }
}
