<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\PasswordReset\Communication\Controller\PasswordResetController;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class PasswordResetControllerTest extends TestCase
{

    private ViewFaker $view;
    private PasswordResetController $passwordResetController;

    protected function setUp(): void{
        parent::setUp();
        $this->view = new ViewFaker();
        $this->passwordResetController = new PasswordResetController();

    }

    protected function tearDown(): void{
        unset($this->view, $_GET, $_POST);
        parent::tearDown();
    }
    public function testResetSuccessfully(): void{

        $this->passwordResetController->load($this->view);

    }
    public function testResetFail(): void{

    }
    public function testTimeoutEmail(): void
    {

    }
}