<?php

namespace App\Controller\Api;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\User\UserSettingService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{

    public function __construct(
        private readonly UserSettingService $userSetting,
    ) {
    }

    #[Route('/api/user/theme', name: 'api_user_theme')]
    public function theme(Request $request): jsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userSetting->updateTheme($user, $request);

        return $this->json(['theme' => 'dark']);
    }


}