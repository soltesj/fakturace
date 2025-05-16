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
        $this->addFlash('info', 'Welcome to the dashboard');
        $this->addFlash('warning', 'You can use the menu on the left to navigate through the application');
        $this->addFlash('error', 'You can use the menu on the left to navigate through the application');
        $this->addFlash('success', 'You can use the menu on the left to navigate through the application');

        return $this->render('dash_board/index.html.twig', [
            'controller_name' => 'DashBoardController',
            'company' => $company,
        ]);
    }
}
