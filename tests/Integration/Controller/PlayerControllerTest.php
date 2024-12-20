<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\PlayerController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{
    private PlayerController $playerController;

    private ViewInterface $view;

    protected function setUp(): void
    {
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequesterFaker(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $footballBusinessFacade = new FootballBusinessFacade($apiRequesterFacade);
        $this->playerController = new PlayerController($footballBusinessFacade, new Redirect());
        parent::setUp();
    }

    protected function tearDown(): void
    {
        if (isset($_GET)) {
            unset($_GET);
        }
        unset($this->view, $this->playerController);
        parent::tearDown();
    }

    public function testController(): void
    {
        $_GET['id'] = '348';

        $this->playerController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertArrayHasKey('playerData', $parameters);

        $playerData = $parameters['playerData'];
        self::assertSame('Rafinha', $playerData['name']);
        self::assertSame('Defence', $playerData['position']);
        self::assertSame('1985-09-07', $playerData['dateOfBirth']);
        self::assertSame('Brazil', $playerData['nationality']);
        self::assertSame(13, $playerData['shirtNumber']);


        self::assertSame('player.twig', $this->view->getTemplate());
        self::assertSame('Rafinha', $parameters['playerName']);
    }

    public function testNoGet(): void
    {
        $this->playerController->load($this->view);
        $parameters = $this->view->getParameters();

        self::assertSame($parameters, []);
        self::assertEmpty($parameters);
    }

    public function testInvalidGet(): void
    {
        $_GET['id'] = '3259069483648';

        $this->playerController->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertEmpty($parameters);
    }
}