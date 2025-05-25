<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use App\Test\Translation\TranslationHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    use TranslationHelperTrait;

    public function testDashboardWorks(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/1/dashboard');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->t('dashboard.overview'));
    }

    public function testDashboardUnauthorizedAccessRedirect(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/2/dashboard');
        $this->assertResponseRedirects('/cs/1/dashboard', 302);
    }
}
