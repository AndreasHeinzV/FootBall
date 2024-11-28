<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\HomeController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Core\SessionHandler;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{




    private ViewFaker $view;

    private HomeController $homeController;

    private SchemaBuilder $schemaBuilder;

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


        $sqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($sqlConnector);



        $this->homeController = new HomeController($footballBusinessFacade);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
        unset( $_SERVER['REQUEST_METHOD'], $_POST['loginButton'], $_POST['email'], $_POST['password']);
        parent::tearDown();
    }

    public function testIndex(): void
    {
        $this->homeController->load($this->view);
        $parameters = $this->view->getParameters();


        self::assertArrayHasKey('leagues', $parameters, "Checking if specific parameter is set");
        $playerData = $parameters['leagues'];

        self::assertCount(13, $playerData, "Checking for league count");
        self::assertSame('Championship', $playerData[1]->name, "expected League");
    }
}