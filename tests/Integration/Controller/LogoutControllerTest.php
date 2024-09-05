<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LogoutController;
use App\Core\SessionHandler;
use App\Model\Mapper\UserMapper;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;


class LogoutControllerTest extends TestCase
{
    protected function setUp(): void
    {
        session_start();
        parent::setUp();
    }

    public function testLogout(): void
    {
        $redirectSpy = new RedirectSpy();
        $mapper = new UserMapper();
        $sessionHandler = new SessionHandler($mapper);
        self::assertSame(session_status(), 2, "2 stands for active");
        $logoutController = new LogoutController($sessionHandler, $redirectSpy);
        $logoutController->load(new ViewFaker());
        self::assertSame(session_status(), 1, "1 stands for none");
    }
}