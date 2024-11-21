<?php

declare(strict_types=1);

namespace App\Core;


use App\Components\Football\Communication\Controller\HomeController;
use App\Components\Football\Communication\Controller\LeaguesController;
use App\Components\Football\Communication\Controller\PlayerController;
use App\Components\Football\Communication\Controller\TeamController;
use App\Components\Pages\Business\Communication\Controller\NoPageController;
use App\Components\PasswordReset\Communication\Controller\PasswordFailedController;
use App\Components\PasswordReset\Communication\Controller\PasswordResetController;
use App\Components\Shop\Communication\DetailsController;
use App\Components\Shop\Communication\ShopController;
use App\Components\UserFavorite\Communication\Controller\FavoriteController;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\UserLogin\Communication\Controller\LogoutController;
use App\Components\UserRegister\Communication\Controller\UserRegisterController;

class ControllerProvider
{

    public string $testData = "";

    public function __construct(private readonly Container $container)
    {
    }

    /**
     * @return array<string, class-string>
     * @phpstan-return array<'home'|'competitions'|'team'|'player'|'register'|'login'|'logout'|'favorites'|'password-failed'|'password-reset'|'404'|'clubShop', class-string>
     */
    public function getList(): array
    {
        return [
            'home' => HomeController::class,
            'competitions' => LeaguesController::class,
            'team' => TeamController::class,
            'player' => PlayerController::class,
            'register' => UserRegisterController::class,
            'login' => LoginController::class,
            'logout' => LogoutController::class,
            'favorites' => FavoriteController::class,
            'password-failed' => PasswordFailedController::class,
            'password-reset' => PasswordResetController::class,
            'shop' => ShopController::class,
            'details' => DetailsController::class,
            '404' => NoPageController::class,
        ];
    }

    public function handlePage(): void
    {
        $page = $_GET['page'] ?? 'home';
        $controllerList = $this->getList();


        $controllerToRender = $controllerList[$page] ?? $controllerList['404'];

        if (isset($_ENV['test'])) {
            $this->testData = $controllerToRender;
        }
        $controller = $this->container->get($controllerToRender);
        $view = $this->container->get(View::class);
        $controller->load($view);
    }
}