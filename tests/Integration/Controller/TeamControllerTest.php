<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\TeamController;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class TeamControllerTest extends TestCase
{
    public function testIndex2(): void
    {
        $_GET['id'] = '3984';
        $view = new ViewFaker();
        $teamController = new TeamController(Container::getRepository());
        $teamController->load($view);
        $parameters = $view->getParameters();

        self::assertArrayHasKey('players', $parameters);
        $playerData = $parameters['players'];
      //  dump($playerData);
        self::assertCount(45, $playerData['squad']);
        self::assertSame(1631, $playerData['squad'][0]->playerID);
    }
}