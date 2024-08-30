<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LoginController;
use App\Model\UserRepository;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{

    public function testLogin(): void
    {
        $viewFaker = new ViewFaker();
        $userRepository = new UserRepository();

        $loginController = new LoginController($userRepository);

        $loginController->load($viewFaker);
        $parameters = $viewFaker->getParameters();
        $template = $viewFaker->getTemplate();

        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters['errors']);
        self::assertArrayHasKey('userDto', $parameters['userDto']);
    }
}