<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;

use App\Components\Football\DTOs\LeaguesDTO;
use App\Components\Football\Mapper\LeaguesMapper;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class LeaguesMapperTest extends TestCase
{
    public array $testData;
    public LeaguesMapper $leaguesMapper;
    public LeaguesDTO $leaguesDTO;

    protected function setUp(): void
    {
        $this->testData = ([
            'id' => 2002,
            'name' => 'Bundesliga',
            'link' => '/index.php?page=competitions&name=BL1',
        ]
        );
        $this->leaguesMapper = new LeaguesMapper();
        $this->leaguesDTO = new LeaguesDTO(2002, 'Bundesliga', '/index.php?page=competitions&name=BL1');
    }

    public function testMapToDto(): void{

    $leaguesDTO = $this->leaguesMapper->createLeaguesDTO($this->testData);
    self::assertSame($this->testData['id'], $leaguesDTO->id);

    }

    public function testMapToArray(): void{
        $outputArray = $this->leaguesMapper->getLeaguesData($this->leaguesDTO);
        assertSame($this->testData['name'], $outputArray['name']);
    }
}