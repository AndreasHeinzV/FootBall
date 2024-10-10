<?php

declare(strict_types=1);

use App\Core\Container;
use App\Core\ControllerProvider;
use App\Core\DependencyProvider;
use App\Core\Fixtures;
use App\Core\View;

session_start();
require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$container = new Container();

$dependencyProvider = new DependencyProvider();
$dependencyProvider->fill($container);
$build = $container->get(Fixtures::class);
$build->buildTables();
$view = $container->get(View::class);

$controllerProvider = new ControllerProvider($container);
$controllerProvider->handlePage();
$view->display();




















