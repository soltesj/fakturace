<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Service\CompanyTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashBoardController extends AbstractController
{
    use CompanyTrait;

    public function __construct(private RequestStack $requestStack, private CompanyRepository $companyRepository)
    {
    }

    #[Route('/{company}/dashboard', name: 'app_dash_board')]
    public function index(Request $request, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'Neopravneny pokus o zmenu adresy');
            return $this->getCorrectCompanyUrl($request, $user);
        }

        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
        ]);
    }

}
