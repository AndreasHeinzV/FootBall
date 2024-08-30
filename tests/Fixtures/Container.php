<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;

class Container
{
    public static function getRepository(): FootballRepository
    {
        return new FootballRepository(
            new ApiRequesterFaker(),
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
    }
}