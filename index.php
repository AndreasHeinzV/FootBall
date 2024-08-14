<?php

session_start();

use App\Controller\CompetitionsController;
use App\Controller\IndexController;
use App\Controller\SquadController;
use App\Controller\PlayerController;


require_once __DIR__ . '/vendor/autoload.php';
$templatePath = __DIR__ . '/src/View';
$page = $_GET['page'] ?? '';


switch ($page) {
    case 'player':

        $test = new PlayerController();
        $test->loadPlayer();
        $test->renderPlayer();
        break;

    case 'competitions':
        $competitionController = new CompetitionsController();
        $competitionController->loadCompetitions();
        $competitionController->renderCompetitions();

        break;

    case 'team':

        $squadController = new SquadController();
        $squadController->loadSquad();
        $squadController->renderSquad();
        break;

    default:

        $indexController = new IndexController();
        $indexController->loadIndex();
        $indexController->renderIndex();
        break;
}
















