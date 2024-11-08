<?php

declare(strict_types=1);
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;


require __DIR__ . '/bootstrap.php';

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);