<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
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
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\DataLoader;
use App\Tests\Fixtures\ViewFaker;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class FavoriteControllerTest extends TestCase
{

    //toDo write case list empty, multiple users in favorites_json
    private ViewFaker $view;

    private FavoriteController $favoriteController;


    private ORMSqlConnector $connector;
    private SchemaBuilder $schemaBuilder;

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $jsonfile = file_get_contents(__DIR__ . '/../../Fixtures/FavoritesBasic/favorites_test.json');
        file_put_contents(__DIR__ . '/../../../favorites_test.json', $jsonfile);

        $_ENV['test'] = 1;
        $_ENV['DATABASE'] = 'football_test';
        $userMapper = new UserMapper();
        $sessionHandler = new SessionHandler($userMapper);
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequesterFaker(
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
        $userDTO = $userMapper->createDTO($testData);


        $this->connector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($this->connector);
        $this->schemaBuilder->createSchema();
        $this->databaseBuilder = new DatabaseBuilder($this->connector);


        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $favoriteMapper = new FavoriteMapper();
        $favoriteRepository = new UserFavoriteRepository($this->connector, $favoriteMapper);

        $favorite = new Favorite(
            $sessionHandler,
            $footballBusinessFacade,
            new UserFavoriteEntityManager($this->connector),
            $favoriteRepository,
            $favoriteMapper
        );
        $userFavoriteBusinessFacade = new userFavoriteBusinessFacade($favorite, $favoriteRepository);
        $this->favoriteController = new FavoriteController($sessionHandler, $userFavoriteBusinessFacade);


        $userEntityManager = new UserEntityManager($this->connector);
        $userEntityManager->saveUser($userDTO);
        $this->databaseBuilder->loadData($userDTO);
    }

    protected function tearDown(): void
    {
        //   $this->databaseBuilder->dropTables();

        $this->schemaBuilder->dropSchema();

        $_SESSION = [];
        $_POST = [];
        unset($_ENV);

        parent::tearDown();
    }

    public function testAdd(): void
    {
        $_ENV['test'] = 1;

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "3984";


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
        self::assertCount(4, $favorites);
        self::assertSame("FC Bayern München", $favorites[0]->teamName, "Base value");
        self::assertSame('Fortaleza EC', $favorites[3]->teamName, "expected team name");
    }

    public function testAddFalse(): void
    {
        $_ENV['test'] = 1;

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "4";


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
        self::assertCount(3, $favorites);
    }


    public function testTryAddForDuplicate(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['add'] = "5";

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
        self::assertSame("FC Bayern München", $favorites[0]->teamName, "Base value");
        self::assertCount(3, $favorites);
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
        $_POST['up'] = 4;
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


        $favorites = $parameters['favorites'];

        self::assertSame('FC Bayern München', $favorites[0]->teamName);
        self::assertCount(3, $favorites);
        self::assertSame('Borussia Dortmund', $favorites[1]->teamName);
    }

    public function testMoveDown(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['down'] = 1770;
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
        $favorites = $parameters['favorites'];

        self::assertSame('Borussia Dortmund', $favorites[1]->teamName);
        self::assertCount(3, $favorites);
        self::assertSame('FC Bayern München', $favorites[0]->teamName);
        self::assertSame('Botafogo FR', $favorites[2]->teamName);
    }
}