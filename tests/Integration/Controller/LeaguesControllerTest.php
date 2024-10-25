<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\LeaguesController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Core\ViewInterface;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class LeaguesControllerTest extends TestCase
{
    private ViewInterface $view;

    private LeaguesController $leaguesController;
    protected function setUp(): void
    {
        parent::setUp();
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequesterFaker(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $this->leaguesController = new LeaguesController(($footballBusinessFacade));

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
        self::assertNotEmpty($playerData[0]->name);
        self::assertNotEmpty($playerData[8]->name);
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