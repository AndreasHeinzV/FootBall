<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\HomeController;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    public function testIndex(): void
    {

        $view = new ViewFaker();
        $teamController = new HomeController(Container::getRepository());
        $teamController->load($view);
        $parameters = $view->getParameters();


        self::assertArrayHasKey('leagues', $parameters, "Checking if specific parameter is set");
        $playerData = $parameters['leagues'];

        self::assertCount(13, $playerData, "Checking for league count");
        self::assertSame('Championship', $playerData[1]->name, "expected League");

    }
}