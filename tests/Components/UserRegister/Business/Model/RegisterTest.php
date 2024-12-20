<?php

declare(strict_types=1);

namespace App\Tests\Components\UserRegister\Business\Model;

use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\UserRegisterValidation;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;
use App\Components\UserRegister\Persistence\Mapper\RegisterMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{

    private Register $register;
    private MockObject $userRegisterValidationMock;

    protected function setUp(): void
    {
        parent::setUp();

        $errorsDto = new ErrorsDTO('', '', '', '');


        $this->userRegisterValidationMock = $this->createMock(UserRegisterValidation::class);
        $this->userRegisterValidationMock->method('userRegisterGetErrorsDTO')->willReturn($errorsDto);
        $userBusinessFacadeDummy = $this->createMock(UserBusinessFacade::class);
        $userRegisterMapperDummy = $this->createMock(RegisterMapper::class);

        $this->register = new Register(
            $this->userRegisterValidationMock,
            $userBusinessFacadeDummy,
            $userRegisterMapperDummy
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterExecuteExpectNull(): void
    {
        $userRegisterDto = new UserRegisterDto();
        $this->userRegisterValidationMock->method('validateNoErrors')->willReturn(true);
        $returnValue = $this->register->execute($userRegisterDto);

        self::assertNull($returnValue);
    }

    public function testRegisterExecutionExpectErrorDto(): void
    {
        $userRegisterDto = new UserRegisterDto();
        $this->userRegisterValidationMock->method('validateNoErrors')->willReturn(false);
        $returnValue = $this->register->execute($userRegisterDto);

        self::assertInstanceOf(ErrorsDTO::class, $returnValue);
    }
}