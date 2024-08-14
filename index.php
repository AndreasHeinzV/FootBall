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

session_start();
require_once __DIR__ . '/vendor/autoload.php';
$templatePath = __DIR__ . '/src/View';
$page = $_GET['page'] ?? '';


$loader = new FilesystemLoader(__DIR__ . '/src/View');
$twig = new Environment($loader);
$repository = new FootballRepository();
$value = [];

// Load the index logic
if (!isset($_GET['page']) && !isset($_GET['code'])) {
    $value['leagues'] = $repository->getLeagues();
}

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
        $playerController->loadPlayer();
        break;

    case 'competitions':
        $competitionController = new LeaguesController();
        $competitionController->loadCompetitions();

        break;

    case 'team':

        $squadController = new TeamController();
        $squadController->loadSquad();
        break;

    case 'logout':
        $logoutController = new LogoutController();
        $logoutController->logout();
        break;

    case 'login':
        $loginController = new LoginController();
        $loginController->doLogin();
        break;

    case 'register':
        $registerController = new RegisterController();
        $registerController->doRegister();
        break;

    default:
        echo $twig->render('index.twig', $value);
        break;
}
















