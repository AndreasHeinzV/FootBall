<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\PlayerController;
use App\Core\ViewInterface;
use App\Model\FootballRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{
    private PlayerController $controller;

    private ViewInterface $view;

    private FootballRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createStub(FootballRepositoryInterface::class);
        $this->controller = new PlayerController($this->repository);
        $this->view = $this->createStub(ViewInterface::class);
    }

    protected function tearDown(): void
    {
        unset($_GET);
        parent::tearDown();
    }

    public function testClassIfGetIsEmpty(): void
    {
        $_GET = [];
        $result = $this->controller->load($this->view);
        self::assertSame([], $result);
    }

    public function testClassIfGetHasValue(): void
    {
        $_GET['id'] = "John";

        $userData = [
            'playerPosition' => 'Goalkeeper',
            'playerDate' => '1996-02-13',
            'playerNationality' => 'Brazil',
            'playerShirtNumber' => '12',
        ];

        $this->repository->method('getPlayer')->willReturn([
                'playerName' => 'John',
                'playerPosition' => 'Goalkeeper',
                'playerDate' => '1996-02-13',
                'playerNationality' => 'Brazil',
                'playerShirtNumber' => '12',
            ]
        );

        $result = $this->controller->load($this->view);
        self::assertSame(['playerName' => 'John', 'playerData' => $userData], $result);
    }
}