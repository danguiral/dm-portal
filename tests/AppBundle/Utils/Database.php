<?php
namespace Tests\AppBundle\Utils;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use \Symfony\Bundle\FrameworkBundle\Client;

class Database
{

    public static function cleanDb(Client $client) {

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $connection = $em->getConnection();
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0;');
        $loader = new Loader();
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0;');
    }

    public static function loadFixtures(Client $client) {
        $client->getContainer()->get('khepin.yaml_loader')->loadFixtures('test');
    }

    public static function prepareDb(Client $client) {
        self::cleanDb($client);
        self::loadFixtures($client);
    }

    public static function getLast(Client $client, $repository) {
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        return $em->getRepository($repository)->findOneBy(
            [],
            ['id' => 'DESC']
        );
    }


}