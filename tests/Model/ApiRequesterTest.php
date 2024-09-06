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

    public function testApiRequestGetCompetition(): void
    {
        $competition = $this->apiRequester->getCompetition('BSA');

        self::assertCount(20, $competition);
        self::assertNotSame('', $competition[0]->name);
    }

    public function testApiRequestGetTeam(): void
    {
        $team = $this->apiRequester->getTeam('3984');
        self::assertNotEmpty($team[0]->name);
    }

    public function testApiRequestGetPlayers(): void
    {
        $leagues = $this->apiRequester->getLeagues();

        $bool = false;
        foreach ($leagues as $league) {
            if ($league->name === 'Bundesliga') {
                $bool = true;
            }
        }
        self::assertTrue($bool);
    }

}