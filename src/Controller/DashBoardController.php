<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Service\CompanyTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashBoardController extends AbstractController
{
    use CompanyTrait;

    #[Route('/{company}/dashboard', name: 'app_dash_board')]
    public function index(Request $request, Company $company): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->getCompanies()->contains($company)) {
            $this->addFlash('warning', 'UNAUTHORIZED_ATTEMPT_TO_CHANGE_ADDRESS');
            return $this->getCorrectCompanyUrl($request, $user);
        }

        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
        ]);
    }

}
