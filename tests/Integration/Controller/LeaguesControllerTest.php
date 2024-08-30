<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LeaguesController;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class LeaguesControllerTest extends TestCase
{
    public function testIndex2(): void
    {
        $_GET['name'] = 'BSA';
        $view = new ViewFaker();
        $leaguesController = new LeaguesController(Container::getRepository());
        $leaguesController->load($view);
        $parameters = $view->getParameters();
        // var_dump($parameters);

        self::assertArrayHasKey('teams', $parameters);
        $playerData = $parameters['teams'];
        self::assertCount(20, $playerData);

        self::assertSame('Fortaleza EC', $playerData[0]->name);
        self::assertSame('CA Mineiro', $playerData[8]->name);
    }
}