<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Controller\LogoutController;
use App\Core\FavoriteHandler;
use App\Core\Redirect;
use App\Core\SessionHandler;
use App\Core\Validation;
use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Model\Mapper\UserMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use SebastianBergmann\CodeUnit\Mapper;

class Container
{
    public static function getRepository(): FootballRepository
    {
        return new FootballRepository(
            new ApiRequesterFaker(
                new LeaguesMapper(),
                new CompetitionMapper(),
                new TeamMapper(),
                new PlayerMapper()
            ),

        );
    }


    public static function getEntityManager(): UserEntityManager
    {
        return new UserEntityManager(
            new Validation(),
            new UserRepository(),
            new UserMapper(),
        );
    }


    public static function getSessionHandler(): SessionHandler
    {
        return new SessionHandler(
            new UserMapper()
        );
    }

    public static function getFavoriteHandler(): FavoriteHandler
    {
        return new FavoriteHandler(
            self::getSessionHandler(),
            self::getRepository(),
            self::getEntityManager(),
            new UserRepository()
        );
    }
}