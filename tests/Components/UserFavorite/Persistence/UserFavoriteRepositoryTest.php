<?php

declare(strict_types=1);

namespace App\Tests\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use PHPUnit\Framework\TestCase;

class UserFavoriteRepositoryTest extends TestCase
{

    private UserFavoriteRepository $userFavoriteRepository;

    protected function setUp(): void
    {
        $this->userFavoriteRepository = new UserFavoriteRepository(new ORMSqlConnector(), new FavoriteMapper());
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