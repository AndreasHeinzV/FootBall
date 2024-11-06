<?php

declare(strict_types=1);

namespace App\Tests\Components\UserLogin\Business;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\UserLogin\Business\Model\Login;
use App\Components\UserLogin\Business\UserLoginBusinessFacade;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserLoginBusinessFacadeTest extends TestCase
{
    private MockObject $loginMock;
    private UserLoginBusinessFacade $businessFacade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginMock = $this->createMock(Login::class);
        $this->businessFacade = new UserLoginBusinessFacade($this->loginMock);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testLoginUserExpectNull(): void
    {
        $this->loginMock->method('execute')->willReturn(null);
        $loginDto = new UserLoginDto();
        $result = $this->businessFacade->loginUser($loginDto);

        self::assertNull($result);
    }

    public function testLoginUserExpectErrorDto(): void
    {
        $this->loginMock->method('execute')->willReturn(new ErrorsDTO('', '', '', ''));
        $loginDto = new UserLoginDto();
        $result = $this->businessFacade->loginUser($loginDto);

        self::assertInstanceOf(ErrorsDTO::class, $result);
    }
}