<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacade;
use App\Components\PasswordReset\Communication\Controller\PasswordFailedController;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class PasswordFailedControllerTest extends TestCase
{

    private PasswordFailedController $controller;

    private ViewFaker $view;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view = new ViewFaker();
        $passwordFailedBusinessFacade = new PasswordResetBusinessFacade();
        $this->controller = new PasswordFailedController($passwordFailedBusinessFacade);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testSendMail(): void
    {
        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        $output = $parameters['output'];
        self::assertNotEmpty($parameters);
        self::assertSame('email send successfully', $output);
    }

    public function testSendMailFailed(): void
    {
        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertNotEmpty($parameters);
        $output = $parameters['output'];
        self::assertSame('No user found with the given email.', $output);
    }
}