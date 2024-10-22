<?php

declare(strict_types=1);

namespace App\Tests\Integration\Facade;

use App\Components\Api\Business\ApiRequestFacade;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\DTOs\PlayerDTO;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;

class FootballBusinessFacadeTest extends TestCase
{

    private FootballBusinessFacade $footballBusinessFacade;

    protected function setUp(): void
    {
        parent::setUp();

        $apiRequester = new ApiRequesterFaker(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequestFacade($apiRequester);
        $this->footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
    }

    public function testGetPlayer(): void
    {
        $playerDTO = $this->footballBusinessFacade->getPlayer('348');
        self::assertInstanceOf(PlayerDTO::class, $playerDTO);
        assertNotNull($playerDTO);
        self::assertSame('Rafinha', $playerDTO->name);
    }

    public function testGetNotExistingPlayer(): void
    {
        $playerDTO = $this->footballBusinessFacade->getPlayer('42354786790');
        self::assertNotInstanceOf(PlayerDTO::class, $playerDTO);
        self::assertNull($playerDTO);
    }

    public function testGetTeam(): void
    {
        $team = $this->footballBusinessFacade->getTeam('3984');
        self::assertNotEmpty($team);
    }

    public function testGetTeamNotExistingId(): void
    {
        $team = $this->footballBusinessFacade->getTeam('398435475895');
        self::assertEmpty($team);
    }


    public function testGetCompetition(): void
    {
        $competition = $this->footballBusinessFacade->getCompetition('BSA');
        self::assertNotEmpty($competition);
        self::assertCount(20, $competition);
    }

    public function testGetCompetitionNotExistingId(): void
    {
        $competition = $this->footballBusinessFacade->getCompetition('BSAGEH');
        self::assertEmpty($competition);
    }

    public function testGetLeagues(): void
    {
        $leagues = $this->footballBusinessFacade->getLeagues();
        assertNotEmpty($leagues);
        self::assertCount(13, $leagues);
    }
}