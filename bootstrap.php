<?php

declare(strict_types=1);
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";


$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src'],
    isDevMode: true,
);

$connection = DriverManager::getConnection([
    'dbname' => 'football',
    'user' => 'root',
    'password' => 'nexus123',
    'host' => 'database',
    'driver' => 'pdo_mysql',
    'port' => '3306',
], $config);


$entityManager = new EntityManager($connection, $config);