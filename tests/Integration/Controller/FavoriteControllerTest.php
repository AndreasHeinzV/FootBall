<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\FavoriteController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Core\FavoriteHandler;
use App\Core\SessionHandler;
use App\Core\Validation;
use App\Model\ApiRequester;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Model\Mapper\UserMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class FavoriteControllerTest extends TestCase
{

    public function testAdd(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'ilovecats@gmail.com';
        $_POST['password'] = '1LoveCats!';
        $_GET['id'] = 1770;
        $_GET['favorite'] ='add';

        $view = new ViewFaker();
        $userMapper = new UserMapper();
        $userRepository = new UserRepository();
        $validation = new Validation();
        $redirectSpy = new RedirectSpy();
        $sessionHandler = new SessionHandler($userMapper);
        $view = new ViewFaker();
        $loginController = new LoginController(
            $userRepository, $userMapper, $validation, $sessionHandler, $redirectSpy
        );
        $apiRequester = new ApiRequester(new LeaguesMapper(), new CompetitionMapper(),new TeamMapper(),new PlayerMapper());
        $repo = new FootballRepository($apiRequester);
       // $userDTO = new UserDTO('testname', '', '', '');
        $userEntityManager = new UserEntityManager($validation, $userRepository, $userMapper);

        $favoriteHandler = new FavoriteHandler($sessionHandler, $repo, $userEntityManager);
        $favController = new FavoriteController($sessionHandler, $favoriteHandler);




        $loginController->load($view);
        $teamController = new HomeController(Container::getRepository(), $sessionHandler);
        $teamController->load($view);
        $favController->load($view);

        $parameters = $view->getParameters();
        dump($parameters);
        $template = $view->getTemplate();


        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
    }
}