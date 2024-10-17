<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;

use App\Components\Football\DTOs\TeamDTO;
use App\Components\Football\Mapper\TeamMapper;
use PHPUnit\Framework\TestCase;

class TeamMapperTest extends TestCase
{
    private array $testData;
    private TeamMapper $teamMapper;

    private TeamDTO $teamDTO;

    public function setUp(): void
    {
        $this->testData = [
            'playerID' => 264054,
            'link' => '/index.php?page=player&id=264054',
            'name' => 'Landerson',

        ];
        $this->teamDTO = new TeamDTO(264054, '/index.php?page=player&id=264054', 'Landerson');

        $this->teamMapper = new TeamMapper();
    }

    public function testCreatTeamDTO(): void
    {

    $teamDTO = $this->teamMapper->createTeamDTO($this->testData);
    self::assertSame($this->testData['name'], $teamDTO->name);

    }

    public function testGetTeamData(): void
    {
        $teamArray = $this->teamMapper->getTeamData($this->teamDTO);
        self::assertIsArray($teamArray);
        self::assertSame($this->testData['link'], $teamArray['link']);

    }
}