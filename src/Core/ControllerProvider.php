<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\HomeController;
use App\Controller\LeaguesController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\PlayerController;
use App\Controller\RegisterController;
use App\Controller\TeamController;

class ControllerProvider
{
    private Container $container;
    private array $data;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getList(): array
    {
        return [
            'home' => HomeController::class,
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
        $page = $_GET['page'] ?? 'home';
        $controllerList = $this->getList();

        $controllerToRender = $controllerList[$page];
        $controller = $this->container->get($controllerToRender);
        $view = $this->container->get(View::class);
        $controller->load($view);
    }
}