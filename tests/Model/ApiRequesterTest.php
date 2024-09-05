<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\ApiRequester;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use PHPUnit\Framework\TestCase;

class ApiRequesterTest extends TestCase
{
    public LeaguesMapper $leaguesMapper;
    public CompetitionMapper $competitionMapper;
    public TeamMapper $teamMapper;
    public PlayerMapper $playerMapper;

    public ApiRequester $apiRequester;

    protected function setUp(): void
    {
        $this->leaguesMapper = new LeaguesMapper();
        $this->competitionMapper = new CompetitionMapper();#
        $this->teamMapper = new TeamMapper();
        $this->playerMapper = new PlayerMapper();
        $this->apiRequester = new ApiRequester(
            $this->leaguesMapper,
            $this->competitionMapper,
            $this->teamMapper,
            $this->playerMapper
        );
        parent::setUp();
    }

    protected function tearDown(): void
    {

        parent::tearDown();
    }

    public function testApiRequestGetPlayer(): void
    {
        $playerDTO = $this->apiRequester->getPlayer('1299');
        self::assertNotEmpty($playerDTO);
        self::assertNotSame('', $playerDTO->name);
    }
}