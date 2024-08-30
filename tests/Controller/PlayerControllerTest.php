<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\PlayerController;
use App\Core\ViewInterface;
use App\Model\DTOs\PlayerDTO;
use App\Model\FootballRepositoryInterface;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{
    private PlayerController $controller;

    private ViewFaker $view;

    private FootballRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createStub(FootballRepositoryInterface::class);
        $this->controller = new PlayerController($this->repository);
        $this->view = new ViewFaker();
    }

    protected function tearDown(): void
    {
        unset($_GET);
        parent::tearDown();
    }

    public function testClassIfGetIsEmpty(): void
    {
        $_GET = [];
        $result = $this->controller->load();
        self::assertSame([], $result);
    }

    public function testClassIfGetHasValue(): void
    {
        $_GET['id'] = "John";

        $player = new PlayerDTO(
            name: 'John',
            position: 'Goalkeeper',
            dateOfBirth: '1996-02-13',
            nationality: 'Brazil',
            shirtNumber: 12,
        );

        $this->repository
            ->method('getPlayer')
            ->willReturn($player);

        $this->controller->load($this->view);

        $paramters = $this->view->getParameters();

        self::assertArrayHasKey('playerData', $paramters);

        $playerData = $paramters['playerData'];
        self::assertSame('John', $playerData['name']);
        self::assertSame('Goalkeeper', $playerData['position']);
        self::assertSame('1996-02-13', $playerData['dateOfBirth']);
        self::assertSame('Brazil', $playerData['nationality']);
        self::assertSame(12, $playerData['shirtNumber']);


        self::assertSame('player.twig', $this->view->getTemplate());

        self::assertSame('John', $paramters['playerName']);
    }
}