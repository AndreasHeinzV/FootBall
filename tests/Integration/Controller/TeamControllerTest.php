<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\TeamController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\UserFavorite\Business\UserFavoriteBusinessFacade;
use App\Core\ViewInterface;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class TeamControllerTest extends TestCase
{
    private TeamController $teamController;
    private ViewInterface $view;


    protected function setUp(): void
    {
        $apiRequester = new ApiRequesterFaker(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $userFavoriteBusinessFacadeMock = $this->createMock(UserFavoriteBusinessFacade::class);

        $this->teamController = new TeamController($footballBusinessFacade, $userFavoriteBusinessFacadeMock);
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
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertArrayHasKey('players', $parameters);
        $playerData = $parameters['players'];
        $playerCount = count($playerData['squad']);

        self::assertTrue($playerCount > 10, "Player Count should always be greater than 10");
        self::assertSame(1631, $playerData['squad'][0]->playerID);
    }

    public function testIndexNoGet(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertNotContains('players', $parameters);
        self::assertEmpty($parameters);
    }

    public function testIndexEmptyTeam(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['id'] = '35624646';

        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertNotContains('players', $parameters);
    }

    public function testIndexTeam(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['test'] = 1;
        $_GET['id'] = '35624646';

        $this->teamController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertNotContains('players', $parameters);
    }

}