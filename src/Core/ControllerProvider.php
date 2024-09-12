<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\FavoriteController;
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

    public string $testData = "";
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
            'favorites' => FavoriteController::class,

        ];
    }

    public function handlePage(): void
    {
        $page = $_GET['page'] ?? 'home';
        $controllerList = $this->getList();

        $controllerToRender = $controllerList[$page];
        if (isset($_ENV) && $_ENV['test']){
            $this->testData = $controllerToRender;
        }
        $controller = $this->container->get($controllerToRender);
        $view = $this->container->get(View::class);
        $controller->load($view);
    }
}