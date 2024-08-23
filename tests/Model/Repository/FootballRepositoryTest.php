<?php

declare(strict_types=1);

namespace App\Tests\Model\Repository;

use App\Model\ApiRequester;
use App\Model\FootballRepository;
use PHPUnit\Framework\TestCase;

class FootballRepositoryTest extends TestCase
{

    private ApiRequester $apiRequester;

    private FootballRepository $footballRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiRequester = new ApiRequester();
        $this->footballRepository = new FootballRepository($this->apiRequester);
    }


    public function testGetPlayer(): void
    {
        $playerData = $this->footballRepository->getPlayer("1299");
        self::assertSame('John', $playerData['playerName']);
    }

    public function testGetTeam(): void
    {
        $teamData = $this->footballRepository->getTeam('1770');
        // var_export($teamData);
        self::assertSame($teamData[0]['playerID'], 1299);
        self::assertSame($teamData[1]['playerID'], 13152);
        self::assertSame($teamData[1]['name'], 'Raul');
        self::assertSame($teamData[3]['name'], 'Rafael');
    }

    public function testGetCompetition(): void
    {
        $competitionData = $this->footballRepository->getCompetition('BSA');
        //var_dump($competitionData);
        self::assertSame($competitionData[0]['name'], 'Botafogo FR');
        self::assertSame($competitionData[1]['name'], 'Fortaleza EC');
        self::assertSame($competitionData[2]['playedGames'], 23);
        self::assertSame($competitionData[7]['goalDifference'], 0);
    }

    public function testGetLeagues(): void
    {
        $leaguesData = $this->footballRepository->getLeagues();


        $link = '/index.php?page=competitions&name=' . 'EC';
        self::assertSame($leaguesData[1]['name'], 'Championship');
        self::assertSame($leaguesData[2]['id'], 2021);
        self::assertSame($leaguesData[3]['name'], 'UEFA Champions League');
        self::assertSame($leaguesData[4]['link'], $link);
    }
}