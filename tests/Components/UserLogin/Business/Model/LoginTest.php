<?php

declare(strict_types=1);

namespace App\Tests\Components\UserLogin\Business\Model;

use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserLogin\Business\Model\Login;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use App\Core\SessionHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;


class LoginTest extends TestCase
{

    private Login $login;
    private MockObject $userLoginValidationMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userLoginValidationMock = $this->createMock(UserLoginValidation::class);
        $sessionHandlerMock = $this->createMock(SessionHandler::class);
        $userBusinessFacadeMock = $this->createMock(UserBusinessFacade::class);

        $this->login = new Login($this->userLoginValidationMock, $userBusinessFacadeMock, $sessionHandlerMock);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testExecutionExpectNull(): void
    {
        $this->userLoginValidationMock->method('userLoginGetErrorsDTO')
            ->willReturn(new ErrorsDTO('', '', '', ''));

        $this->userLoginValidationMock->method('validateNoErrors')->willReturn(true);

        $result = $this->login->execute(new UserLoginDto());

        self::assertNull($result);
    }

    public function testExecutionExpectErrorDto(): void
    {
        $this->userLoginValidationMock->method('userLoginGetErrorsDTO')
            ->willReturn(new ErrorsDTO('', '', '', ''));

        $this->userLoginValidationMock->method('validateNoErrors')->willReturn(false);
        $result = $this->login->execute(new UserLoginDto());

        self::assertInstanceOf(ErrorsDTO::class, $result);
    }
}