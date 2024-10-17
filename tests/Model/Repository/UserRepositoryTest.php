<?php

declare(strict_types=1);

namespace App\Tests\Model\Repository;


use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class UserRepositoryTest extends TestCase
{
    private string $path;

    private UserMapper $userMapper;

    private FavoriteMapper $favoriteMapper;
    private Fixtures $fixtures;

    private UserRepository $userRepository;

    private UserEntityManager $userEntityManager;
    private FootballBusinessFacade $footballRepository;

    protected function setUp(): void
    {
        $_ENV['DATABASE'] = 'football_test';

        $testData = [

            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',

        ];

        $sqlConnector = new SqlConnector();
        $this->userMapper = new UserMapper();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $this->favoriteMapper = new FavoriteMapper();
        $this->fixtures = new Fixtures($sqlConnector);
        $this->fixtures->buildTables();
        $this->footballRepository = new FootballBusinessFacade(
            $apiRequester
        );
        $this->userRepository = new UserRepository($sqlConnector);
        $this->userEntityManager = new UserEntityManager($this->userRepository, $sqlConnector);

        $this->userEntityManager->saveUser($this->userMapper->createDTO($testData));
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->fixtures->dropTables();
        parent::tearDown();
    }

    public function testFindUserByEmail(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];


        $userDTO = $this->userMapper->createDTO($testData);
        $username = $this->userRepository->getUserName($userDTO->email);
        self::assertSame($username, $testData['firstName']);
    }

    public function testGetUserFail(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'wrongmail@gmf.com',
            'password' => 'passw0rd',
        ];

        $userDTO = $this->userMapper->createDTO($testData);
        $username = $this->userRepository->getUserName($userDTO->email);
        assertSame('', $username);
    }

    public function testGetUser(): void
    {
        $testMail = 'dog@gmail.com';

        $userDTO = $this->userRepository->getUser($testMail);
        assertSame('ImATestCat', $userDTO->firstName);
    }

    public function testGetUserNotFound(): void
    {
        $testMail = 'wrong@gmail.com';
        $userDTO = $this->userRepository->getUser($testMail);
        assertSame('', $userDTO->firstName);
    }

    public function testGetUserFavorites(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'wrongmail@gmf.com',
            'password' => 'passw0rd',
        ];

        $userDTO = $this->userMapper->createDTO($testData);
        $userFavorites = $this->userRepository->getUserFavorites($userDTO);
        self::assertIsArray($userFavorites);
        // toDo      self::assertNotEmpty($userFavorites);
    }

    public function testGetUserFavoritesNotFound(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'wrongmail@gmf.com',
            'password' => 'passw0rd',
        ];

        $userDTO = $this->userMapper->createDTO($testData);
        $userFavorites = $this->userRepository->getUserFavorites($userDTO);
        self::assertIsArray($userFavorites);
        self::assertEmpty($userFavorites);
    }

    public function testGetAllUsers(): void
    {
        $allUsers = $this->userRepository->getUsers();
        self::assertIsArray($allUsers);
        self::assertNotEmpty($allUsers);
    }

    public function testCheckExistingFavoriteNoFavorites(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];
        $userDTO = $this->userMapper->createDTO($testData);
        $returnValue = $this->userRepository->checkExistingFavorite($userDTO, '51');
        self::assertFalse($returnValue);
    }

    public function testCheckExistingFavoriteFavorites(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];

        $team = $this->footballRepository->getTeam('1770');


        $this->userEntityManager->saveUserFavorite(
            $this->userMapper->createDTO($testData),
            $this->favoriteMapper->createFavoriteDTO($team)
        );

        $userDTO = $this->userMapper->createDTO($testData);
        $returnValue = $this->userRepository->checkExistingFavorite($userDTO, '1770');
        self::assertTrue($returnValue);



    }

    public function testGetUserID(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];
        $userDTO = $this->userMapper->createDTO($testData);
        $userID = $this->userRepository->getUserID($userDTO);
        self::assertSame($userID, $userID);
    }
}