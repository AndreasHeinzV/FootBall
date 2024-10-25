<?php

declare(strict_types=1);

namespace App\Tests\Components\Football;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\DTOs\PlayerDTO;
use App\Components\Football\DTOs\TeamDTO;
use App\Components\Football\Mapper\PlayerMapper;
use PHPUnit\Framework\TestCase;

class FootballBusinessFacadeTest extends TestCase
{

    private FootballBusinessFacade $businessFacade;


    private $apiRequesterMock;

    protected function setUp(): void
    {
        $this->apiRequesterMock = $this->createMock(ApiRequesterFacade::class);
    }

    public function testGetPlayerDto(): void
    {
        $this->apiRequesterMock->method('getPlayer')->willReturn((new PlayerDTO('', '', '', '', 1)));
        $this->businessFacade = new FootballBusinessFacade($this->apiRequesterMock);

        $result = $this->businessFacade->getPlayer('3');

        self::assertInstanceOf(PlayerDTO::class, $result);
    }

    public function testGetPlayerIsNull(): void
    {
        $this->apiRequesterMock->method('getPlayer')->willReturn(null);
        $this->businessFacade = new FootballBusinessFacade($this->apiRequesterMock);

        $result = $this->businessFacade->getPlayer('3');

        self::assertNull($result);
    }

    public function testGetTeamArray(): void{

        $this->apiRequesterMock->method('getTeam')->willReturn([]);
        $this->businessFacade = new FootballBusinessFacade($this->apiRequesterMock);

        $result = $this->businessFacade->getTeam('3');

        self::assertIsArray( $result);
    }

    public function testGetCompetitionArray(): void{

        $this->apiRequesterMock->method('getCompetition')->willReturn([]);
        $this->businessFacade = new FootballBusinessFacade($this->apiRequesterMock);

        $result = $this->businessFacade->getCompetition('3');

        self::assertIsArray( $result);
    }
    public function testGetLeaguesArray(): void{

        $this->apiRequesterMock->method('getLeagues')->willReturn([]);
        $this->businessFacade = new FootballBusinessFacade($this->apiRequesterMock);

        $result = $this->businessFacade->getCompetition('3');

        self::assertIsArray( $result);
    }
}