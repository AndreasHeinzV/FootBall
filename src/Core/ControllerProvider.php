<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\FavoriteController;
use App\Controller\HomeController;
use App\Controller\LeaguesController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\NoPageController;
use App\Controller\PlayerController;
use App\Controller\RegisterController;
use App\Controller\TeamController;

class ControllerProvider
{

    public string $testData = "";
    public function __construct(private readonly Container $container)
    {

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
            '404' => NoPageController::class,
        ];
    }

    public function handlePage(): void
    {
        $page = $_GET['page'] ?? 'home';
        $controllerList = $this->getList();


        if (empty($controllerList[$page])) {
            $controllerToRender = $controllerList['404'];
        }else{
            $controllerToRender = $controllerList[$page];
        }

        if (isset($_ENV['test'])){
            $this->testData = $controllerToRender;
        }
        $controller = $this->container->get($controllerToRender);
        $view = $this->container->get(View::class);
        $controller->load($view);
    }
}