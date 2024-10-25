<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Communication\Controller\HomeController;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\Pages\Business\Communication\Controller\NoPageController;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\UserLogin\Communication\Controller\LogoutController;
use App\Core\Container;
use App\Core\ControllerProvider;
use App\Core\Redirect;
use App\Core\SessionHandler;
use App\Core\View;
use App\Tests\Fixtures\ApiRequest\ApiRequesterFaker;
use App\Tests\Fixtures\RedirectSpy;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerProviderTest extends TestCase
{

    protected function tearDown(): void
    {
        unset($this->controllerProvider, $this->container);
        parent::tearDown();
    }
    public function testControllerProvider(): void{

        $_GET['page'] = 'logout';
        $_ENV['test']= '1';

        $container = new Container();
        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../../src/View'));
        $container->set(UserMapper::class, new UserMapper());
        $container->set(Redirect::class, new RedirectSpy());
        $container->set(SessionHandler::class, new SessionHandler(
            $container->get(UserMapper::class)
        ));
        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));

        $container->set(View::class, new View(
            $container->get(Environment::class),
            $container->get(SessionHandler::class),
        ));
        $container->set(LogoutController::class, new LogoutController(
            $container->get(SessionHandler::class),
            $container->get(Redirect::class),
        ));


        $controllerProvider = new ControllerProvider($container);
        $list = $controllerProvider->getList();
        $controllerProvider->handlePage();


        self::assertSame($list['home'],HomeController::class);
        self::assertSame($controllerProvider->testData, LogoutController::class);
        self::assertNotSame(HomeController::class, $controllerProvider->testData);
    }
    public function testControllerProviderNoPage(): void{
        $_ENV['test']= '';

        $container = new Container();
        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../../src/View'));
        $container->set(UserMapper::class, new UserMapper());
        $container->set(LeaguesMapper::class, new LeaguesMapper());
        $container->set(CompetitionMapper::class, new CompetitionMapper());
        $container->set(TeamMapper::class, new TeamMapper());
        $container->set(PlayerMapper::class, new PlayerMapper());
        $container->set(Redirect::class, new RedirectSpy());
        $container->set(SessionHandler::class, new SessionHandler(
            $container->get(UserMapper::class)
        ));
        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));

        $container->set(View::class, new View(
            $container->get(Environment::class),
            $container->get(SessionHandler::class),
        ));

        $container->set(ApiRequesterFaker::class, new ApiRequesterFaker(
            $container->get(LeaguesMapper::class),
            $container->get(CompetitionMapper::class),
            $container->get(TeamMapper::class),
            $container->get(PlayerMapper::class)
        ));
        $container->set(ApiRequesterFacade::class, new ApiRequesterFacade(
            $container->get(ApiRequesterFaker::class),
        ));
        $container->set(FootballBusinessFacade::class, new FootballBusinessFacade(
            $container->get(ApiRequesterFacade::class)
        ));

        $container->set(HomeController::class, new HomeController(
            $container->get(FootballBusinessFacade::class),
        ));

        $controllerProvider = new ControllerProvider($container);
        $controllerProvider->handlePage();



        self::assertSame( HomeController::class, $controllerProvider->testData);
    }
    public function testNoPage(): void{
        $_ENV['test']= '';
        $_GET['page'] = 'wwagahah';
        $container = new Container();
        $container->set(FilesystemLoader::class, new FilesystemLoader(__DIR__ . '/../../src/View'));
        $container->set(UserMapper::class, new UserMapper());
        $container->set(Environment::class, new Environment(
            $container->get(FilesystemLoader::class)));
        $container->set(SessionHandler::class, new SessionHandler(
            $container->get(UserMapper::class)
        ));
        $container->set(View::class, new View(
            $container->get(Environment::class),
            $container->get(SessionHandler::class),
        ));
        $container->set(NoPageController::class, new NoPageController());

        $controllerProvider = new ControllerProvider($container);
        $controllerProvider->handlePage();
        self::assertSame(NoPageController::class, $controllerProvider->testData);
    }
}