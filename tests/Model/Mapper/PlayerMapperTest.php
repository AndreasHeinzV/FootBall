<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;

use App\Components\Football\DTOs\PlayerDTO;
use App\Components\Football\Mapper\PlayerMapper;
use PHPUnit\Framework\TestCase;

class PlayerMapperTest extends TestCase
{
    public function testDtoToArray(): void
    {
        $mapper = new PlayerMapper();

        $playerDTO = new PlayerDTO('John', 'Defence', '1985-09-07', 'Brazil', 13);
        $playerArray = $mapper->getPlayerData($playerDTO);

        self::assertIsArray($playerArray);
        self::assertSame('John', $playerArray['playerName']);
        self::assertSame('Defence', $playerArray['playerPosition']);
        self::assertSame('1985-09-07', $playerArray['playerDateOfBirth']);
    }
}