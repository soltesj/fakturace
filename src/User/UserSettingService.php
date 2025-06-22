<?php

namespace App\User;

use App\Entity\User;
use App\Entity\UserSetting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserSettingService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function updateTheme(User $user, Request $request): void
    {
        $setting = $user->getSetting();
        if (!$setting instanceof UserSetting) {
            $setting = new UserSetting();
            $user->setSetting($setting);
        }
        $data = json_decode($request->getContent(), true);
        dump($data);
        $theme = $data['theme'] ?? 'dark';
        $setting->setTheme($theme);
        $this->em->persist($setting);
        $this->em->flush();
    }
}