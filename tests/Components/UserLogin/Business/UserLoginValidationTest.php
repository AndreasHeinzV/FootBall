<?php

declare(strict_types=1);

namespace App\Tests\Components\UserLogin\Business;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\EmailLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserLoginValidationTest extends TestCase
{

    private UserLoginValidation $userLoginValidation;

    private MockObject $emailLoginValidationMock;

    private MockObject $passwordLoginValidationMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailLoginValidationMock = $this->createMock(EmailLoginValidation::class);
        $this->passwordLoginValidationMock = $this->createMock(PasswordLoginValidation::class);
        $errorMapper = new ErrorMapper();
        $this->userLoginValidation = new UserLoginValidation(
            $errorMapper,
            $this->emailLoginValidationMock,
            $this->passwordLoginValidationMock
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUserLoginGetErrorsDto(): void
    {
        $userLoginDto = new UserLoginDto();
        $userLoginDto->email = 'test@g.com';
        $userLoginDto->password = '1234';


        $this->emailLoginValidationMock
            ->expects($this->once())
            ->method('validateInput')
            ->with($userLoginDto)
            ->willReturn('Invalid email format');

        $this->passwordLoginValidationMock
            ->expects($this->once())
            ->method('validateInput')
            ->with($userLoginDto)
            ->willReturn('Password too short');

        $result = $this->userLoginValidation->userLoginGetErrorsDTO($userLoginDto);

        self::assertInstanceOf(ErrorsDTO::class, $result);
        self::assertSame('Invalid email format', $result->emailError);
        self::assertSame('Password too short', $result->passwordError);
    }

    public function testValidateNoErrorsExpectTrue(): void
    {
        $errorsDTO = new ErrorsDTO(null, null, null, null);
        $hasNoErrors = $this->userLoginValidation->validateNoErrors($errorsDTO);

        self::assertTrue($hasNoErrors);
    }

    public function testValidateNoErrorsExpectFalse(): void
    {
        $errorsDTO = new ErrorsDTO('EmptyFirstName', null, null, null);
        $hasNoErrors = $this->userLoginValidation->validateNoErrors($errorsDTO);
        self::assertFalse($hasNoErrors);
    }

}