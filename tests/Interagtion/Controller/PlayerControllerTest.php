<?php

declare(strict_types=1);

namespace App\Tests\Interagtion\Controller;

use App\Controller\PlayerController;
use App\Model\Mapper\PlayerMapper;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{

    public function testIndex()
    {
        $_GET['id'] = '348';
        $view = new ViewFaker();

        $playerController = new PlayerController(Container::getRepository());
        $playerController->load($view);

        $parameters = $view->getParameters();

        self::assertArrayHasKey('playerData', $parameters);

        $playerData = $parameters['playerData'];
        self::assertSame('Rafinha', $playerData['name']);
        self::assertSame('Defence', $playerData['position']);
        self::assertSame('1985-09-07', $playerData['dateOfBirth']);
        self::assertSame('Brazil', $playerData['nationality']);
        self::assertSame(13, $playerData['shirtNumber']);


        self::assertSame('player.twig', $view->getTemplate());
        self::assertSame('Rafinha', $parameters['playerName']);
    }

}