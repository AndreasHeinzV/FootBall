<?php

declare(strict_types=1);

namespace App\Core;


use App\Components\Football\Communication\Controller\HomeController;
use App\Components\Football\Communication\Controller\LeaguesController;
use App\Components\Football\Communication\Controller\PlayerController;
use App\Components\Football\Communication\Controller\TeamController;
use App\Components\Pages\Business\Communication\Controller\NoPageController;
use App\Components\UserFavorite\Communication\Controller\FavoriteController;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\UserLogin\Communication\Controller\LogoutController;
use App\Components\UserRegister\Communication\Controller\RegisterController;

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