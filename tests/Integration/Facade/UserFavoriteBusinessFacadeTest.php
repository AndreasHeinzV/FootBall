<?php

declare(strict_types=1);

namespace App\Tests\Integration\Facade;

use App\Components\Api\Business\ApiRequestFacade;
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
use App\Tests\Fixtures\ViewFaker;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;

class UserFavoriteBusinessFacadeTest extends TestCase
{

    private UserFavoriteBusinessFacade $businessFacade;

    private DatabaseBuilder $databaseBuilder;

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

        $sqlConnector = new SqlConnector();
        $this->databaseBuilder = new DatabaseBuilder($sqlConnector);
        $this->databaseBuilder->buildTables();

        $apiRequesterFacade = new ApiRequestFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $favoriteMapper = new FavoriteMapper();
        $favoriteRepository = new UserFavoriteRepository($sqlConnector);

        $favorite = new Favorite(
            $sessionHandler,
            $footballBusinessFacade,
            new UserFavoriteEntityManager($sqlConnector),
            $favoriteRepository,
            $favoriteMapper
        );
        $this->businessFacade = new userFavoriteBusinessFacade($favorite, $favoriteRepository);



        $userEntityManager = new UserEntityManager($sqlConnector);
        $userEntityManager->saveUser($userDTO);
        $this->databaseBuilder->loadData($userDTO);

    }
    protected function tearDown(): void
    {
        unset($_ENV);
        $this->databaseBuilder->dropTables();
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



        $status= $this->businessFacade->getFavoriteStatus('4');
        self::assertTrue($status);
    }
}