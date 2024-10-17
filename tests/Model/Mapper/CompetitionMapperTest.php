<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;

use App\Components\Football\DTOs\CompetitionDTO;
use App\Components\Football\Mapper\CompetitionMapper;
use PHPUnit\Framework\TestCase;

class CompetitionMapperTest extends TestCase
{
    public function testMapper(): void
    {
        $mapper = new CompetitionMapper();
        $competitionDTO = new CompetitionDTO(
            4,
            'Botafogo FR',
            'lh',
            3,
            3,
            3,
            3,
            34,
            23,
            21,
            2
        );

        $compData = $mapper->getCompetitionData( $competitionDTO);
        self::assertIsArray($compData);
        self::assertCount(11, $compData);
        self::assertArrayHasKey('name', $compData);
        self::assertSame('Botafogo FR', $compData['name']);
    }
}