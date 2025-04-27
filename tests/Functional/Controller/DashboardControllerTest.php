<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testDashboardWorks(): void
    {
        $client = static::createClient();
//        $user = self::$container->get(UserRepository::class)->findOneByEmail('admin@example.com');
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/1/dashboard');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello DashBoardController!');
    }

    public function testDashboardUnauthorizedAccessWorks(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/2/dashboard');
        $this->assertResponseRedirects('/cs/1/dashboard', 302);
    }

    //UserDoesNotHaveAccessToCompany
//;
}
