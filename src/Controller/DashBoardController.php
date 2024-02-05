<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Service\CompanyTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardController extends AbstractController
{
    use CompanyTrait;

    public function __construct(private RequestStack $requestStack, private CompanyRepository $companyRepository)
    {
        $this->session = $requestStack->getSession();
    }

    #[Route('/dashboard', name: 'app_dash_board')]
    public function index(): Response
    {
        $company = $this->getCompany();

        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
        ]);
    }
}
