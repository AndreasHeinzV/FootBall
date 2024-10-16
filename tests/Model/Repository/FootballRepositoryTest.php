<?php

declare(strict_types=1);

namespace App\Tests\Model\Repository;

use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\DTOs\TeamDTO;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use PHPUnit\Framework\TestCase;

class FootballRepositoryTest extends TestCase
{

    private ApiRequesterFaker $apiRequester;

    private FootballBusinessFacade $footballRepository;
    public TeamMapper $teamMapper;

    public TeamDTO $teamDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->teamMapper = new TeamMapper();
        $this->apiRequester = new ApiRequesterFaker(
            new LeaguesMapper(),
            new CompetitionMapper(),
            $this->teamMapper,
            new PlayerMapper()
        );


        $this->footballRepository = new FootballBusinessFacade(
            $this->apiRequester,

        );
    }

    protected function tearDown(): void
    {
        unset($this->apiRequester, $this->footballRepository, $this->teamMapper, $this->teamDTO);
        parent::tearDown();
    }


    public function testGetPlayer(): void
    {
        $playerDTO = $this->footballRepository->getPlayer("348");
        self::assertSame('Rafinha', $playerDTO->name);
    }

    public function testGetTeam(): void
    {
        $teamData = $this->footballRepository->getTeam('3984');
        //var_export($teamData);

        $this->teamDTO = $teamData['squad'][0];
        self::assertSame($this->teamDTO->playerID, 1631);

        //$this->teamDTO = $teamData[1];
        self::assertSame($teamData['squad'][1]->playerID, 1662);
        self::assertSame($teamData['squad'][1]->name, 'Santos');
        self::assertSame($teamData['squad'][3]->name, 'Magrão');
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