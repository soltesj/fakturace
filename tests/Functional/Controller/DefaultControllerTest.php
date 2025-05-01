<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageWork(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cs');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello it\'s home page');
    }

    public function testHomepageRedirectLocale(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseRedirects('/en', 302);
    }
}
