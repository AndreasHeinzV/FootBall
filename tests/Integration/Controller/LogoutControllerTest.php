<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LogoutController;
use App\Core\ViewInterface;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

session_start();

class LogoutControllerTest extends TestCase
{

    public function testLogout(): void
    {
        self::assertSame(session_status(), 2, "2 stands for active");
        $logoutController = new LogoutController();
        $logoutController->load(new ViewFaker());
        self::assertSame(session_status(), 1, "1 stands for none");
    }
}