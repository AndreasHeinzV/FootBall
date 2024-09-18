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
use App\Core\ViewInterface;
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
    private SessionHandler $sessionHandler;
    private UserMapper $userMapper;
    private UserRepository $userRepository;
    private Validation $validation;

    private ViewFaker $view;

    private ApiRequester $apiRequester;

    private FootballRepository $repo;
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

        $this->repo = new FootballRepository($this->apiRequester);
        $this->userEntityManager = new UserEntityManager($this->validation, $this->userRepository, $this->userMapper);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unlink(__DIR__ . '/../../../favorites_test.json');
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
        // dump($favorites);
        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("Botafogo FR", $favorites[0]['teamName'], "Base value");
        self::assertNotEmpty($favorites[1]['teamName']);
        self::assertNotEmpty($favorites[1]['teamName'], 'Burnley FC', "expected 2nd Team");
    }

}