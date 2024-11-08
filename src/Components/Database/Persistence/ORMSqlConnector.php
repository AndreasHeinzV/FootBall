<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class ORMSqlConnector
{
    private EntityManager $entityManager;

    public function __construct()
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/Entity'],
            isDevMode: true,
        );
        $dbName = $_ENV['DATABASE'] ?? 'football';

        $connection = DriverManager::getConnection([
            'dbname' => $dbName,
            'user' => 'root',
            'password' => 'nexus123',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
            'port' => '3336',
        ], $config);

        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}