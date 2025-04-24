<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashBoardController extends AbstractController
{
    #[Route('/{_locale}/{company}/dashboard', name: 'app_dash_board')]
    public function index( Company $company): Response
    {
        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
        ]);
    }

}
