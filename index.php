<?php

declare(strict_types=1);

use App\Core\ControllerProvider;
use App\Model\ApiKeyHandler;
use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$loader = new FilesystemLoader(__DIR__ . '/src/View');
$twig = new Environment($loader);
//$repository = new FootballRepository();
$value = [];
$apikeyHandler = new ApiKeyHandler();
$controllerProvider = new ControllerProvider();

$controllerProvider->handlePage();

$loginStatus = false;
$sessionUsername = '';

if (!isset($_GET['page']) && !isset($_SESSION['loginStatus'])) {
    $value['leagues'] = $repository->getLeagues();
    echo $twig->render('home.twig', $value);
}

if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus']) {
    $sessionUsername = $_SESSION['userName'];
    $loginStatus = $_SESSION['loginStatus'];
    $value['userName'] = $sessionUsername;
    $value['status'] = $loginStatus;
    $value['leagues'] = $repository->getLeagues();
    echo $twig->render('home.twig', $value);
}


















