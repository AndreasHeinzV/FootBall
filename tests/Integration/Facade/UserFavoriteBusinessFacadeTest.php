<?php

declare(strict_types=1);

namespace App\Tests\Integration\Facade;

use App\Components\Api\Business\ApiRequesterFacade;
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
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use App\Core\SessionHandler;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\ViewFaker;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\TestCase;

class UserFavoriteBusinessFacadeTest extends TestCase
{

    private UserFavoriteBusinessFacade $businessFacade;

    private DatabaseBuilder $databaseBuilder;

    private SchemaBuilder $schemaBuilder;

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function setUp(): void
    {
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

        $sqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($sqlConnector);
        $this->schemaBuilder->createSchema();
        $this->databaseBuilder = new DatabaseBuilder($sqlConnector);


        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $favoriteMapper = new FavoriteMapper();
        $favoriteRepository = new UserFavoriteRepository($sqlConnector, $favoriteMapper);

        $favorite = new Favorite(
            $sessionHandler,
            $footballBusinessFacade,
            new UserFavoriteEntityManager($sqlConnector),
            $favoriteRepository,
            $favoriteMapper
        );
        $this->businessFacade = new userFavoriteBusinessFacade($favorite, $favoriteRepository);


        $ORMSqlConnector = new ORMSQLConnector();
        $userEntityManager = new UserEntityManager($ORMSqlConnector);
        $userEntityManager->saveUser($userDTO);
        $this->databaseBuilder->loadData($userDTO);
    }

    protected function tearDown(): void
    {
        unset($_ENV);
      $this->schemaBuilder->dropSchema();
    }

    public function testGetFavoriteStatus(): void
    {
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];


        $status = $this->businessFacade->getFavoriteStatus('4');
        self::assertTrue($status);
    }
}