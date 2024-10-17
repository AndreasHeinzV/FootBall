<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Football\Communication\Controller\TeamController;
use App\Core\ManageFavorites;
use App\Core\ViewInterface;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class TeamControllerTest extends TestCase
{
    private TeamController $teamController;
    private ViewInterface $view;

    protected function setUp(): void
    {
        $this->teamController = new TeamController(
            Container::getRepository(),
            Container::getFavoriteHandler(),
            new ManageFavorites(Container::getSessionHandler(), Container::getFavoriteHandler())
        );
        $this->view = new ViewFaker();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        if (isset($this->teamController)) {
            unset($this->teamController, $this->view);
        }
        unset($_GET);
        parent::tearDown();
    }

    public function testController(): void
    {
        $_GET['id'] = '3984';
        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertArrayHasKey('players', $parameters);
        $playerData = $parameters['players'];

        self::assertCount(45, $playerData['squad']);
        self::assertSame(1631, $playerData['squad'][0]->playerID);
    }

    public function testIndexNoGet(): void
    {


        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertNotContains('players', $parameters);
        self::assertEmpty($parameters);

    }

    public function testIndexEmptyTeam(): void
    {
        $_GET['id'] = '35624646';

        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();
            $page = $_GET['page'];

        self::assertNotContains('players', $parameters);
//        self::assertSame([], $parameters['players']);
        //dump($page);

    }
}