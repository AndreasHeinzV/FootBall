<?php

declare(strict_types=1);

namespace App\Tests\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use App\Tests\Fixtures\DatabaseBuilder;
use PHPUnit\Framework\TestCase;

class UserFavoriteRepositoryTest extends TestCase
{

    private UserFavoriteRepository $userFavoriteRepository;
    private UserMapper $userMapper;

    private SchemaBuilder $schemaBuilder;


    protected function setUp(): void
    {
        $ormSqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($ormSqlConnector);
        $this->schemaBuilder->createSchema();
        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $this->userMapper = new UserMapper();
        $userDTO = $this->userMapper->createDTO($testData);
        $this->schemaBuilder->fillTables($userDTO);

        $this->userFavoriteRepository = new UserFavoriteRepository($ormSqlConnector, new FavoriteMapper());
    }

    protected function tearDown(): void
    {
        unset($_POST, $_GET);
        $this->schemaBuilder->clearDatabase();
        parent::tearDown();
    }

    public function testGetUserFavoritesEmptyEntries(): void
    {
        $userDto = new UserDto(2, 'test', '', '', '');
        $favorites = $this->userFavoriteRepository->getUserFavorites($userDto);
        self::assertEmpty($favorites);
    }

    public function testTryGetPositionAbove(): void
    {
        $userDto = new UserDto(2, 'test', '', '', '');

        $favorite = $this->userFavoriteRepository->getFavoritePositionAboveCurrentPosition($userDto, 1);
        self::assertFalse($favorite);
    }

    public function testTryGetPositionBelow(): void
    {
        $userDto = new UserDto(2, 'test', '', '', '');

        $favorite = $this->userFavoriteRepository->getFavoritePositionBelowCurrentPosition($userDto, 1);
        self::assertFalse($favorite);
    }
}