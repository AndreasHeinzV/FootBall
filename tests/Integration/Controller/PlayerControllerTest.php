<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\PlayerController;
use App\Core\ViewInterface;
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
        $this->playerController = new PlayerController(Container::getRepository());
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

    public function testInvalidGet(): void{
        $_GET['id'] = '3259069483648';

        $this->playerController->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertEmpty($parameters);
    }
}