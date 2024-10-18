<?php

declare(strict_types=1);

namespace App\Core;

use App\Components\Api\Business\ApiRequestFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\HomeController;
use App\Components\Football\Communication\Controller\LeaguesController;
use App\Components\Football\Communication\Controller\PlayerController;
use App\Components\Football\Communication\Controller\TeamController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\Pages\Business\Communication\Controller\NoPageController;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserFavorite\Business\Model\Favorite;
use App\Components\UserFavorite\Business\UserFavoriteBusinessFacade;
use App\Components\UserFavorite\Communication\Controller\FavoriteController;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use App\Components\UserLogin\Business\Model\Login;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Business\UserLoginBusinessFacade;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\UserLogin\Communication\Controller\LogoutController;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\UserRegisterValidation;
use App\Components\UserRegister\Business\UserRegisterBusinessFacade;
use App\Components\UserRegister\Communication\Controller\RegisterController;
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
        $container->set(FavoriteMapper::class, new FavoriteMapper());
        $container->set(NoPageController::class, new NoPageController());
        $container->set(ErrorMapper::class, new ErrorMapper());
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
        $container->set(DatabaseBusinessFacade::class, new DatabaseBusinessFacade(
            $container->get(Fixtures::class),
        ));
        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../View'));

        $container->set(UserRepository::class, new UserRepository(
            $container->get(SqlConnector::class)));

        $container->set(UserFavoriteRepository::class, new UserFavoriteRepository(
            $container->get(SqlConnector::class),
        ));
        $container->set(UserEntityManager::class, new UserEntityManager(
            $container->get(SqlConnector::class)

        ));
        $container->set(UserBusinessFacade::class, new UserBusinessFacade(
            $container->get(UserRepository::class),
            $container->get(UserEntityManager::class)
        ));
        $container->set(UserLoginValidation::class, new UserLoginValidation(
            $container->get(UserBusinessFacade::class),
            $container->get(ErrorMapper::class)
        ));

        $container->set(UserFavoriteEntityManager::class, new UserFavoriteEntityManager(
            $container->get(SqlConnector::class),
        ));
        $container->set(ApiRequestFacade::class, new ApiRequestFacade(
            $container->get(ApiRequester::class),
        ));

        $container->set(SessionHandler::class, new SessionHandler(
            $container->get(UserMapper::class)
        ));
        $container->set(FootballBusinessFacade::class, new FootballBusinessFacade(
            $container->get(ApiRequestFacade::class),
        ));
        $container->set(Favorite::class, new Favorite(
            $container->get(SessionHandler::class),
            $container->get(FootballBusinessFacade::class),
            $container->get(UserFavoriteEntityManager::class),
            $container->get(UserFavoriteRepository::class),
            $container->get(FavoriteMapper::class),
        ));

        $container->set(UserFavoriteBusinessFacade::class, new UserFavoriteBusinessFacade(
            $container->get(Favorite::class),
            $container->get(UserFavoriteRepository::class)
        ));
        $container->set(UserRegisterValidation::class, new UserRegisterValidation(
            $container->get(ErrorMapper::class),
        ));

        $container->set(Register::class, new Register(
            $container->get(UserRegisterValidation::class),
            $container->get(UserBusinessFacade::class),
        ));
        $container->set(UserRegisterBusinessFacade::class, new UserRegisterBusinessFacade(
            $container->get(UserBusinessFacade::class),
            $container->get(Register::class),
        ));
        $container->set(LogoutController::class, new LogoutController(
            $container->get(SessionHandler::class),
            $container->get(Redirect::class),
        ));

        $container->set(FavoriteController::class, new FavoriteController(
            $container->get(SessionHandler::class),
            $container->get(UserFavoriteBusinessFacade::class)
        ));
        $container->set(Login::class, new Login(
            $container->get(UserLoginValidation::class),
            $container->get(UserBusinessFacade::class),
            $container->get(SessionHandler::class),
        ));
        $container->set(UserLoginBusinessFacade::class, new UserLoginBusinessFacade(
            $container->get(Login::class)
        ));
        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));

        $container->set(View::class, new View(
            $container->get(Environment::class),
            $container->get(SessionHandler::class),
        ));
        $container->set(LoginController::class, new LoginController(
            $container->get(UserLoginBusinessFacade::class),
            $container->get(Redirect::class)
        ));

        $container->set(RegisterController::class, new RegisterController(
            $container->get(UserRegisterBusinessFacade::class),
            $container->get(Redirect::class)
        ));

        $container->set(PlayerController::class, new PlayerController(
            $container->get(FootballBusinessFacade::class)
        ));

        $container->set(TeamController::class, new TeamController(
            $container->get(FootballBusinessFacade::class),
            $container->get(UserFavoriteBusinessFacade::class),


        ));
        $container->set(LeaguesController::class, new LeaguesController(
            $container->get(FootballBusinessFacade::class)
        ));

        $container->set(HomeController::class, new HomeController(
            $container->get(FootballBusinessFacade::class),
        ));
    }


}