<?php
declare(strict_types=1);

use App\Controller\LeaguesController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\RegisterController;
use App\Controller\TeamController;
use App\Controller\PlayerController;
use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use \App\Model\ApiKeyHandler;

session_start();
require_once __DIR__ . '/vendor/autoload.php';


$loader = new FilesystemLoader(__DIR__ . '/src/View');
$twig = new Environment($loader);
$repository = new FootballRepository();
$value = [];
$apikeyHandler = new ApiKeyHandler();
// Load the index logic
if (!isset($_GET['page']) && !isset($_GET['code'])) {
    $value['leagues'] = $repository->getLeagues();
}

$page = $_GET['page'] ?? '';
$loginStatus = false;
$sessionUsername = '';

if (isset($_SESSION['loginStatus']) && $_SESSION['loginStatus']) {
    $sessionUsername = $_SESSION['userName'];
    $loginStatus = $_SESSION['loginStatus'];
}


$value['userName'] = $sessionUsername;
$value['status'] = $loginStatus;


switch ($page) {
    case 'player':

        $playerController = new PlayerController();
        $playerController->load();
        break;

    case 'competitions':
        $competitionController = new LeaguesController();
        $competitionController->load();

        break;

    case 'team':

        $squadController = new TeamController();
        $squadController->load();
        break;

    case 'logout':
        $logoutController = new LogoutController();
        $logoutController->load();
        break;

    case 'login':
        $loginController = new LoginController();
        $loginController->load();
        break;

    case 'register':
        $registerController = new RegisterController();
        $registerController->load();
        break;

    default:
        echo $twig->render('home.twig', $value);
        break;
}
















