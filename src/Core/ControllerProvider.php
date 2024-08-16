<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\Controller;
use App\Controller\LeaguesController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\PlayerController;
use App\Controller\RegisterController;
use App\Controller\TeamController;

class ControllerProvider
{
    public function getList(): array
    {
        return [

            'competitions' => LeaguesController::class,
            'team' => TeamController::class,
            'player' => PlayerController::class,
            'register' => RegisterController::class,
            'login' => LoginController::class,
            'logout' => LogoutController::class,


        ];
    }

    public function handlePage(): void
    {
        $page = $_GET['page'] ?? 'competitions';

        $controllerList = $this->getList();
        $controller = $controllerList[$page];
        $controller = new $controller();
        $controller->load();
    }

}