<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LeaguesController;
use App\Core\ViewInterface;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class LeaguesControllerTest extends TestCase
{
    private ViewInterface $view;

    private LeaguesController $leaguesController;
    protected function setUp(): void
    {
        $this->view = new ViewFaker();
        $this->leaguesController = new LeaguesController(Container::getRepository());
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->view, $this->leaguesController, $_GET);
        parent::tearDown();

    }

    public function testController(): void
    {
        $_GET['name'] = 'BSA';

        $this->leaguesController->load($this->view);
        $parameters = $this->view->getParameters();


        self::assertArrayHasKey('teams', $parameters);
        $playerData = $parameters['teams'];
        self::assertCount(20, $playerData);

        self::assertSame('Fortaleza EC', $playerData[0]->name);
        self::assertSame('CA Mineiro', $playerData[8]->name);
    }

    public function testNoGet(): void
    {
        $this->leaguesController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertNotContains('teams', $parameters);
        self::assertSame([], $parameters);
    }
    public function testWrongName(): void{
        $_GET['name'] = 'Ahesrknws';
        $this->leaguesController->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertNotContains('teams', $parameters);
        self::assertSame([], $parameters);
    }
}