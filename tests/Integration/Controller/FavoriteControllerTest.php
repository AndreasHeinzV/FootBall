<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\FavoriteController;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\TeamController;
use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
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

    //toDo write case list empty, multiple users in favorites_json
    protected function setUp(): void
    {
        $jsonfile =file_get_contents(__DIR__.'/../../Fixtures/FavoritesBasic/favorites_test.json');
        file_put_contents(__DIR__.'/../../../favorites_test.json', $jsonfile);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unlink(__DIR__.'/../../../favorites_test.json');
        parent::tearDown();
    }
    public function testAdd(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['id'] = "1770";
        $_GET['favorite'] = 'add';

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];

        $userMapper = new UserMapper();
        $userRepository = new UserRepository();
        $validation = new Validation();
        $sessionHandler = new SessionHandler($userMapper);
        $view = new ViewFaker();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );

        $repo = new FootballRepository($apiRequester);
        $userEntityManager = new UserEntityManager($validation, $userRepository, $userMapper);
        $favoriteHandler = new FavoriteHandler($sessionHandler, $repo, $userEntityManager, $userRepository);
        $manageFavorites = new ManageFavorites($sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($view);
        $parameters = $view->getParameters();
        $template = $view->getTemplate();


        $favorites = $parameters['favorites'];
        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("Botafogo FR", $favorites[0]['teamName']);
    }

}