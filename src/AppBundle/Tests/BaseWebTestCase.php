<?php
namespace AppBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        /**
         * @var Symfony\Bundle\FrameworkBundle\Client;
         */
        $this->client = self::createClient();

        $conn = $this->client
            ->getContainer()
            ->get('database_connection');

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
        $conn->executeQuery('TRUNCATE TABLE user');
        $conn->executeQuery('TRUNCATE TABLE ticket');
        $conn->executeQuery('TRUNCATE TABLE message');
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS = 1');


        $loader  = new Loader();
        $loader->loadFromDirectory('src/AppBundle/DataFixtures');
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);

        $executor->execute($loader->getFixtures(), true);
    }

}