<?php

declare(strict_types=1);

namespace App\Test\PhpUnitExtension;

use App\Kernel;
use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Runner\BeforeFirstTestHook;

use function assert;
use function printf;

/**
 * Load fixtures before first test case, this allows dama/doctrine-test-bundle to work on top of correct data even
 *  when something went wrong in previous run
 */
final class FixtureLoaderExtension implements BeforeFirstTestHook
{
    public function executeBeforeFirstTest(): void
    {
        printf("Loading fixtures\n");
        $kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
        $kernel->boot();
        $loader = $kernel->getContainer()->get('test.service_container')->get('doctrine.fixtures.loader');
        assert($loader instanceof SymfonyFixturesLoader);
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        assert($entityManager instanceof EntityManagerInterface);
        // Není potřeba začínat transakci, nebudeš používat savepointy
        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $fixtureExecutor = new ORMExecutor($entityManager, $purger);
        $fixtureExecutor->execute($loader->getFixtures());
    }
}
