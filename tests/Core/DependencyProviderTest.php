<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Controller\PlayerController;
use App\Core\Container;
use App\Core\DependencyProvider;

use PHPUnit\Framework\TestCase;

class DependencyProviderTest extends TestCase
{
    public function testDependencies(): void
    {
        $dependencyProvider = new DependencyProvider();
        $container = new Container();
        $dependencyProvider->fill($container);

        $playerController = $container->get(PlayerController::class);
        $this->assertInstanceOf(PlayerController::class, $playerController);
    }
}