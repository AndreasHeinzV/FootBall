<?php

declare(strict_types=1);

namespace App\Tests\Model\Repository;

use App\Model\ApiRequester;
use App\Model\DTOs\TeamDTO;
use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Tests\Fixtures\Container;
use PHPUnit\Framework\TestCase;

class FootballRepositoryTest extends TestCase
{

    private ApiRequester $apiRequester;

    private FootballRepository $footballRepository;
    public TeamMapper $teamMapper;

    public TeamDTO $teamDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiRequester = new ApiRequester();
        $this->teamMapper = new TeamMapper();

        $this->footballRepository = new FootballRepository(
            $this->apiRequester,
            new LeaguesMapper(),
            new CompetitionMapper(),
            $this->teamMapper,
            new PlayerMapper()
        );
    }


    public function testGetPlayer(): void
    {
        $playerDTO = $this->footballRepository->getPlayer("1299");
        self::assertSame('John', $playerDTO->name);
    }

    public function testGetTeam(): void
    {
        $teamData = $this->footballRepository->getTeam('1770');
        //var_export($teamData);

        $this->teamDTO = $teamData[0];
        self::assertSame($this->teamDTO->playerID, 1299);

        //$this->teamDTO = $teamData[1];
        self::assertSame($teamData[1]->playerID, 13152);
        self::assertSame($teamData[1]->name, 'Raul');
        self::assertSame($teamData[3]->name, 'Rafael');
    }

       public function testGetCompetition(): void
       {
           $competitionData = $this->footballRepository->getCompetition('BSA');
           //var_dump($competitionData);
           self::assertSame($competitionData[0]->name, 'Fortaleza EC');
           self::assertSame($competitionData[1]->name, 'Botafogo FR');
           self::assertSame($competitionData[2]->playedGames, 24);
           self::assertSame($competitionData[7]->goalDifference, -6);
       }

       public function testGetLeagues(): void
       {
           $leaguesData = $this->footballRepository->getLeagues();
           $link = '/index.php?page=competitions&name=' . 'EC';
           self::assertSame($leaguesData[1]->name, 'Championship');
           self::assertSame($leaguesData[2]->id, 2021);
           self::assertSame($leaguesData[3]->name, 'UEFA Champions League');
           self::assertSame($leaguesData[4]->link, $link);

    }
}