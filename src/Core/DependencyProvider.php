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
use App\Model\ApiRequester;
use App\Model\FootballRepository;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DependencyProvider
{

    public function fill(Container $container): void
    {
        $container->set(ApiRequester::class, new ApiRequester());

        $container->set(LogoutController::class, new LogoutController());

        $container->set(Validation::class, new Validation());

        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../View'));

        $container->set(UserRepository::class, new UserRepository());

        $container->set(UserEntityManager::class, new UserEntityManager());

        $container->set(SessionHandler::class, new SessionHandler());


        $container->set(FootballRepository::class, new FootballRepository(
            $container->get(ApiRequester::class)
        ));


        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));

        $container->set(View::class, new View(
            $container->get(Environment::class),
        ));
        $container->set(LoginController::class, new LoginController(
            $container->get(Environment::class),
            $container->get(UserRepository::class)
        ));

        $container->set(RegisterController::class, new RegisterController(
            $container->get(Environment::class),
            $container->get(UserEntityManager::class),
            $container->get(Validation::class)
        ));

        $container->set(PlayerController::class, new PlayerController(
            $container->get(Environment::class),
            $container->get(FootballRepository::class),
        ));

        $container->set(TeamController::class, new TeamController(
            $container->get(Environment::class),
            $container->get(FootballRepository::class),
        ));
        $container->set(LeaguesController::class, new LeaguesController(
            $container->get(Environment::class),
            $container->get(FootballRepository::class),
        ));

        $container->set(HomeController::class, new HomeController(
            $container->get(Environment::class),
            $container->get(FootballRepository::class)
        ));
    }
}