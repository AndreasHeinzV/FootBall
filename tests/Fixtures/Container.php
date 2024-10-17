<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\Validation\Validation;
use App\Core\FavoriteHandler;
use App\Core\SessionHandler;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;

class Container
{
    public static function getRepository(): FootballBusinessFacade
    {
        return new FootballBusinessFacade(
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