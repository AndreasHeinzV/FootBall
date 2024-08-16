<?php

declare(strict_types=1);

namespace App\Controller;

class ControllerProvider
{
    public function getList(): array
    {
        return [
            LoginController::class,
            RegisterController::class,
            IndexController::class,
            LeaguesController::class,
            LoginController::class,
            PlayerController::class,
            TeamController::class

        ];
    }
}