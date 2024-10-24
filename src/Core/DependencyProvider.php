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
use App\Components\PasswordReset\Business\Model\PasswordFailed\ActionIdGenerator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailBuilder;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailDispatcher;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailValidationPasswordFailed;
use App\Components\PasswordReset\Business\Model\PasswordReset\AccessManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetErrorDtoProvider;
use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\ValidateResetErrors;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateDuplicatePassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateFirstPassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateSecondPassword;
use App\Components\PasswordReset\Business\PasswordResetBusinessFacade;
use App\Components\PasswordReset\Communication\Controller\PasswordFailedController;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
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
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\EmailLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\UserAuthentication;
use App\Components\UserLogin\Business\UserLoginBusinessFacade;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\UserLogin\Communication\Controller\LogoutController;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\UserRegisterValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\EmailValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\FirstNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\LastNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\PasswordValidation;
use App\Components\UserRegister\Business\UserRegisterBusinessFacade;
use App\Components\UserRegister\Communication\Controller\RegisterController;
use App\Components\UserRegister\Persistence\Mapper\RegisterMapper;
use PHPMailer\PHPMailer\PHPMailer;
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
        $container->set(RegisterMapper::class, new RegisterMapper());
        $container->set(NoPageController::class, new NoPageController());
        $container->set(ErrorMapper::class, new ErrorMapper());
        $container->set(FirstNameValidation::class, new FirstNameValidation());
        $container->set(LastNameValidation::class, new LastNameValidation());
        $container->set(EmailValidation::class, new EmailValidation());
        $container->set(PasswordValidation::class, new PasswordValidation());
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
        $container->set(UserAuthentication::class, new UserAuthentication(
            $container->get(UserBusinessFacade::class),
        ));
        $container->set(EmailLoginValidation::class, new EmailLoginValidation());
        $container->set(PasswordLoginValidation::class, new PasswordLoginValidation(
            $container->get(UserAuthentication::class),
        ));
        $container->set(UserLoginValidation::class, new UserLoginValidation(
            $container->get(ErrorMapper::class),
            $container->get(EmailLoginValidation::class),
            $container->get(PasswordLoginValidation::class)
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
            $container->get(FirstNameValidation::class),
            $container->get(LastNameValidation::class),
            $container->get(EmailValidation::class),
            $container->get(PasswordValidation::class),
        ));

        $container->set(Register::class, new Register(
            $container->get(UserRegisterValidation::class),
            $container->get(UserBusinessFacade::class),
            $container->get((RegisterMapper::class))
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
        $container->set(UserPasswordResetRepository::class, new UserPasswordResetRepository(
           $container->get(SqlConnector::class)
        ));

        $container->set(ActionMapper::class, new ActionMapper());
        $container->set(TimeManager::class, new TimeManager());

        $container->set(AccessManager::class, new AccessManager(
            $container->get(UserPasswordResetRepository::class),
            $container->get(ActionMapper::class),
            $container->get(TimeManager::class)
        ));
        $container->set(PHPMailer::class, new PHPMailer());

        $container->set(EmailBuilder::class, new EmailBuilder());
        $container->set(EmailDispatcher::class , new EmailDispatcher(
            $container->get(PHPMailer::class),
        ));
        $container->set(EmailValidationPasswordFailed::class, new EmailValidationPasswordFailed());
        $container->set(ActionIdGenerator::class, new ActionIdGenerator());

        $container->set(UserPasswordResetEntityManager::class, new UserPasswordResetEntityManager(
            $container->get(SqlConnector::class),
        ));
        $container->set(ValidateFirstPassword::class, new ValidateFirstPassword());
        $container->set(ValidateSecondPassword::class, new ValidateSecondPassword());
        $container->set(ValidateDuplicatePassword::class, new ValidateDuplicatePassword());

        $container->set(ValidateResetErrors::class, new ValidateResetErrors(
            $container->get(ValidateFirstPassword::class),
            $container->get(ValidateSecondPassword::class),
            $container->get(ValidateDuplicatePassword::class),
        ));
        $container->set(ResetErrorDtoProvider::class, new ResetErrorDtoProvider(
            $container->get(ValidateResetErrors::class),
        ));

        $container->set(EmailCoordinator::class, new EmailCoordinator(
            $container->get(EmailBuilder::class),
            $container->get(EmailDispatcher::class),
            $container->get(EmailValidationPasswordFailed::class),
            $container->get(TimeManager::class),
            $container->get(ActionIdGenerator::class),
            $container->get(UserPasswordResetEntityManager::class),
            $container->get(UserBusinessFacade::class),
        ));
        $container->set(ResetCoordinator::class, new ResetCoordinator(
            $container->get(ResetErrorDtoProvider::class),
            $container->get(UserPasswordResetRepository::class),
            $container->get(UserPasswordResetEntityManager::class),
            $container->get(UserBusinessFacade::class),
            $container->get(UserMapper::class),
        ));

        $container->set(PasswordResetBusinessFacade::class, new PasswordResetBusinessFacade(
            $container->get(EmailCoordinator::class),
            $container->get(ResetCoordinator::class),
            $container->get(AccessManager::class)
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
            $container->get(FootballBusinessFacade::class),
            $container->get(Redirect::class)
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
        $container->set(PasswordFailedController::class, new PasswordFailedController(
            $container->get(PasswordResetBusinessFacade::class),
            $container->get(Redirect::class),
        ));
    }


}