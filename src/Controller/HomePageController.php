<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}