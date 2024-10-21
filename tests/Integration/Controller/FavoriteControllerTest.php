<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequestFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\UserFavorite\Business\Model\Favorite;
use App\Components\UserFavorite\Business\UserFavoriteBusinessFacade;
use App\Components\UserFavorite\Communication\Controller\FavoriteController;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use App\Core\SessionHandler;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\DataLoader;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class FavoriteControllerTest extends TestCase
{

    //toDo write case list empty, multiple users in favorites_json
    private SessionHandler $sessionHandler;

    private ViewFaker $view;

    private FavoriteController $favoriteController;


    private UserFavoriteBusinessFacade $userFavoriteBusinessFacade;

    private DatabaseBuilder $databaseBuilder;

    protected function setUp(): void
    {
        $jsonfile = file_get_contents(__DIR__ . '/../../Fixtures/FavoritesBasic/favorites_test.json');
        file_put_contents(__DIR__ . '/../../../favorites_test.json', $jsonfile);

        $_ENV['test'] = 1;
        $_ENV['DATABASE'] = 'football_test';
        $this->userMapper = new UserMapper();
        $this->sessionHandler = new SessionHandler($this->userMapper);
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userDTO = $this->userMapper->createDTO($testData);

        $sqlConnector = new SqlConnector();
        $this->databaseBuilder = new DatabaseBuilder($sqlConnector);
        $this->databaseBuilder->buildTables();

        $apiRequesterFacade = new ApiRequestFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $favoriteMapper = new FavoriteMapper();
        $favoriteRepository = new UserFavoriteRepository($sqlConnector);

        $favorite = new Favorite(
            $this->sessionHandler,
            $footballBusinessFacade,
            new UserFavoriteEntityManager($sqlConnector),
            $favoriteRepository,
            $favoriteMapper
        );
        $this->userFavoriteBusinessFacade = new userFavoriteBusinessFacade($favorite, $favoriteRepository);
        $this->favoriteController = new FavoriteController($this->sessionHandler, $this->userFavoriteBusinessFacade);


        $userEntityManager = new UserEntityManager($sqlConnector);
        $userEntityManager->saveUser($userDTO);
        $this->databaseBuilder->loadData($userDTO);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->databaseBuilder->dropTables();
        $_SESSION = [];
        $_POST = [];
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
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];


        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];


        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("FC Bayern München", $favorites[0]['teamName'], "Base value");
        // self::assertNotEmpty($favorites[2]['teamName']);
        //  self::assertSame($favorites[3]['teamName'], 'Burnley FC', "expected 2nd Team");
    }

    public function testTryAddForDuplicate(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "5";
        //  $_POST['favorite'] = 'add';

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];

        // $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '5']);


        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];

        self::assertSame('favorites.twig', $template);
        self::assertNotEmpty($parameters);
        self::assertSame("FC Bayern München", $favorites[0]['teamName'], "Base value");
        //   self::assertCount(2, $favorites);
    }

    public function testDelete(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['delete'] = "5";

        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];
        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();
        $template = $this->view->getTemplate();


        $favorites = $parameters['favorites'];
        self::assertCount(0, $favorites);
        self::assertSame('favorites.twig', $template);
    }

    public function testMoveUp(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['up'] = "4";
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];
        $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '5']);
        $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '4']);

        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();


        $favorites = $parameters['favorites'];

        self::assertSame('FC Bayern München', $favorites[1]['teamName']);
        self::assertCount(2, $favorites);
        self::assertSame('Borussia Dortmund', $favorites[0]['teamName']);
    }

    public function testMoveDown(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['down'] = "1770";
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];
        $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '5']);
        $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '1770']);
        $this->userFavoriteBusinessFacade->manageFavoriteInput(['add' => '4']);

        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();
        $favorites = $parameters['favorites'];

        self::assertSame('Borussia Dortmund', $favorites[1]['teamName']);
        self::assertCount(3, $favorites);
        self::assertSame('FC Bayern München', $favorites[0]['teamName']);
    }

    public function testAddThree(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['down'] = "1770";
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];

        $userDTO = $this->userMapper->createDTO($_SESSION['userDto']);

        $userFavorites = $this->userFavoriteBusinessFacade->getUserFavorites($userDTO);
        $dataLoader = new DataLoader();
        $dataLoader->loadTestDataIntoDatabase($userDTO);


        $this->favoriteController->load($this->view);
        $parameters = $this->view->getParameters();
        $favorites = $parameters['favorites'];

        self::assertSame('FC Bayern München', $favorites[0]['teamName']);
    }
}