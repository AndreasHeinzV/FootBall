<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Controller\HomeController;
use App\Controller\LogoutController;
use App\Core\Container;
use App\Core\ControllerProvider;
use App\Core\Redirect;
use App\Core\SessionHandler;
use App\Core\View;
use App\Model\FootballRepository;
use App\Model\Mapper\CompetitionMapper;
use App\Model\Mapper\LeaguesMapper;
use App\Model\Mapper\PlayerMapper;
use App\Model\Mapper\TeamMapper;
use App\Model\Mapper\UserMapper;
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
        $container->set(FootballRepository::class, new FootballRepository(
            $container->get(ApiRequesterFaker::class)
        ));

        $container->set(HomeController::class, new HomeController(
            $container->get(FootballRepository::class),
        ));

        $controllerProvider = new ControllerProvider($container);
        $controllerProvider->handlePage();



        self::assertSame( HomeController::class, $controllerProvider->testData);
    }
}