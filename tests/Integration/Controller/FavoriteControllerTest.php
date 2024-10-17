<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\Validation\Validation;
use App\Controller\FavoriteController;
use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
use App\Core\SessionHandler;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class FavoriteControllerTest extends TestCase
{

    //toDo write case list empty, multiple users in favorites_json
    private SessionHandler $sessionHandler;
    private UserMapper $userMapper;
    private UserRepository $userRepository;
    private Validation $validation;

    private ViewFaker $view;

    private ApiRequester $apiRequester;

    private FootballBusinessFacade $repo;
    private UserEntityManager $userEntityManager;


    protected function setUp(): void
    {
        $jsonfile = file_get_contents(__DIR__ . '/../../Fixtures/FavoritesBasic/favorites_test.json');
        file_put_contents(__DIR__ . '/../../../favorites_test.json', $jsonfile);

        $_ENV['test'] = 1;
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository();
        $this->validation = new Validation();
        $this->sessionHandler = new SessionHandler($this->userMapper);
        $this->view = new ViewFaker();
        $this->apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );

        $this->repo = new FootballBusinessFacade($this->apiRequester);
        $this->userEntityManager = new UserEntityManager($this->validation, $this->userRepository, $this->userMapper);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unlink(__DIR__ . '/../../../favorites_test.json');
        $_SESSION = [];
        $_POST=[];
        unset($_ENV);
        parent::tearDown();
    }

    public function testAdd(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "328";
        //  $_POST['favorite'] = 'add';

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];


        $favoriteHandler = new FavoriteHandler(
            $this->sessionHandler,
            $this->repo,
            $this->userEntityManager,
            $this->userRepository
        );
        $manageFavorites = new ManageFavorites($this->sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($this->sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];
        //dump($favorites);
        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("Botafogo FR", $favorites[1]['teamName'], "Base value");
        self::assertNotEmpty($favorites[2]['teamName']);
        self::assertSame($favorites[3]['teamName'], 'Burnley FC', "expected 2nd Team");
    }
    public function testTryAddForDuplicate(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "5";
        //  $_POST['favorite'] = 'add';

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];


        $favoriteHandler = new FavoriteHandler(
            $this->sessionHandler,
            $this->repo,
            $this->userEntityManager,
            $this->userRepository
        );
        $manageFavorites = new ManageFavorites($this->sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($this->sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];

        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("Botafogo FR", $favorites[1]['teamName'], "Base value");
        self::assertCount(2, $favorites);


    }
    public function testDelete(): void{
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['delete'] = "5";

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];
        $favoriteHandler = new FavoriteHandler(
            $this->sessionHandler,
            $this->repo,
            $this->userEntityManager,
            $this->userRepository
        );
        $manageFavorites = new ManageFavorites($this->sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($this->sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];
        self::assertCount(1, $favorites);
        self::assertSame('favorites.twig', $template);
    }

    public function testMoveUp(): void{
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['up'] = "5";
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];

        $favoriteHandler = new FavoriteHandler(
            $this->sessionHandler,
            $this->repo,
            $this->userEntityManager,
            $this->userRepository
        );
        $manageFavorites = new ManageFavorites($this->sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($this->sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];

            self::assertSame('FC Bayern München', $favorites[1]['teamName']);
            self::assertCount(2, $favorites);
            self::assertSame('Botafogo FR', $favorites[2]['teamName']);
    }
    public function testMoveDown(): void{
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['down'] = "1770";
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "ilovecats@gmail.com",
            'password' => "1LoveCats!",
        ];

        $favoriteHandler = new FavoriteHandler(
            $this->sessionHandler,
            $this->repo,
            $this->userEntityManager,
            $this->userRepository
        );
        $manageFavorites = new ManageFavorites($this->sessionHandler, $favoriteHandler);
        $favController = new FavoriteController($this->sessionHandler, $favoriteHandler, $manageFavorites);


        $favController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];

        self::assertSame('Botafogo FR', $favorites[2]['teamName']);
        self::assertCount(2, $favorites);
        self::assertSame('FC Bayern München', $favorites[1]['teamName']);
    }
}