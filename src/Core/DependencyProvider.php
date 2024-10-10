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
use App\Model\ApiRequester;
use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Model\Mapper\UserMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DependencyProvider
{

    public function fill(Container $container): void
    {


        $container->set(UserMapper::class, new UserMapper());
        $container->set(LeaguesMapper::class, new LeaguesMapper());
        $container->set(CompetitionMapper::class, new CompetitionMapper());
        $container->set(TeamMapper::class, new TeamMapper());
        $container->set(PlayerMapper::class, new PlayerMapper());
        $container->set(Redirect::class, new Redirect());
        $container->set(NoPageController::class, new NoPageController());
        $container->set(ApiRequester::class, new ApiRequester(
            $container->get(LeaguesMapper::class),
            $container->get(CompetitionMapper::class),
            $container->get(TeamMapper::class),
            $container->get(PlayerMapper::class)
        ));
        $container->set(SqlConnector::class, new SqlConnector());
        $container->set(Fixtures::class, new Fixtures(
            $container->get(SqlConnector::class)
        ));
        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../View'));

        $container->set(UserRepository::class, new UserRepository(
            $container->get(SqlConnector::class)));
        $container->set(Validation::class, new Validation(
            $container->get(UserRepository::class),
        ));
        $container->set(UserEntityManager::class, new UserEntityManager(
            $container->get(UserRepository::class),
            $container->get(UserMapper::class),
            $container->get(SqlConnector::class)

        ));

        $container->set(SessionHandler::class, new SessionHandler(
            $container->get(UserMapper::class)
        ));

        $container->set(LogoutController::class, new LogoutController(
            $container->get(SessionHandler::class),
            $container->get(Redirect::class),
        ));
        $container->set(FootballRepository::class, new FootballRepository(
            $container->get(ApiRequester::class),

        ));

        $container->set(FavoriteHandler::class, new FavoriteHandler(
            $container->get(SessionHandler::class),
            $container->get(FootballRepository::class),
            $container->get(UserEntityManager::class),
            $container->get(UserRepository::class),
        ));
        $container->set(ManageFavorites::class, new ManageFavorites(
            $container->get(SessionHandler::class),
            $container->get(FavoriteHandler::class),
        ));

        $container->set(FavoriteController::class, new FavoriteController(
            $container->get(SessionHandler::class),
            $container->get(FavoriteHandler::class),
            $container->get(ManageFavorites::class),
        ));
        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));

        $container->set(View::class, new View(
            $container->get(Environment::class),
            $container->get(SessionHandler::class),
        ));
        $container->set(LoginController::class, new LoginController(
            $container->get(UserRepository::class),
            $container->get(UserMapper::class),
            $container->get(Validation::class),
            $container->get(SessionHandler::class),
            $container->get(Redirect::class)
        ));

        $container->set(RegisterController::class, new RegisterController(
            $container->get(UserEntityManager::class),
            $container->get(Validation::class),
            $container->get(UserMapper::class),
            $container->get(Redirect::class)
        ));

        $container->set(PlayerController::class, new PlayerController(
            $container->get(FootballRepository::class)
        ));

        $container->set(TeamController::class, new TeamController(
            $container->get(FootballRepository::class),
            $container->get(FavoriteHandler::class),
            $container->get(ManageFavorites::class),

        ));
        $container->set(LeaguesController::class, new LeaguesController(
            $container->get(FootballRepository::class)
        ));

        $container->set(HomeController::class, new HomeController(
            $container->get(FootballRepository::class),
        ));
    }


}