<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentControllerIndexTest extends WebTestCase
{
    public function testDocumentIndexWorks(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/1/document');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Faktury - vydané');
    }

    public function testDocumentIndexUnauthorizedAccessRedirect(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/2/document');
        $this->assertResponseRedirects('/cs/1/document', 302);
    }


    public function testDocumentNewWorks(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/1/document/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Document');
    }

    public function testDocumentNewUnauthorizedAccessRedirect(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
        $client->loginUser($user);
        $crawler = $client->request('GET', '/cs/2/document/new');
        $this->assertResponseRedirects('/cs/1/document/new', 302);
    }

//    public function testDocumentEditWorks(): void
//    {
//        $client = static::createClient();
//        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
//        $client->loginUser($user);
//        $crawler = $client->request('GET', '/cs/1/document/new');
//
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Create new Document');
//    }
//
//    public function testDocumentEditUnauthorizedAccessRedirect(): void
//    {
//        $client = static::createClient();
//        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail(AppFixtures::USER_1_EMAIL);
//        $client->loginUser($user);
//        $crawler = $client->request('GET', '/cs/2/document/new');
//
//        $this->assertResponseRedirects('/cs/1/document/new',302);
//    }
}
