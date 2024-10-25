<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Business\Validation\ResetUserPassword;

use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateSecondPassword;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;
use PHPUnit\Framework\TestCase;

class ValidateSecondPasswordTest extends TestCase
{
    private ValidateSecondPassword $validateSecondPassword;
    private ResetErrorDTO $resetErrorDto;

    private ResetDTO $resetDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validateSecondPassword = new ValidateSecondPassword();
        $this->resetErrorDto = new ResetErrorDto();
        $this->resetDTO = new ResetDTO();
    }

    protected function tearDown(): void
    {
        unset($this->resetErrorDto, $this->resetDTO);
        parent::tearDown();
    }

    public function testValidateFirstPassword(): void
    {
        $this->resetDTO->SecondPassword = "IlikeCats123#";
        $this->resetErrorDto = $this->validateSecondPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNull($this->resetErrorDto->emptySecondPW);
        self::assertNull($this->resetErrorDto->secondPWValidationError);

    }

    public function testValidateFirstPasswordEmpty(): void
    {
        $this->resetDTO->SecondPassword = "";
        $this->resetErrorDto = $this->validateSecondPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNotNull($this->resetErrorDto->emptySecondPW);
        self::assertNull($this->resetErrorDto->secondPWValidationError);

    }

    public function testValidateFirstPasswordIncorrect(): void
    {   $this->resetDTO->SecondPassword = '325fe';
        $this->resetErrorDto = $this->validateSecondPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNull($this->resetErrorDto->emptySecondPW);
        self::assertNotNull($this->resetErrorDto->secondPWValidationError);

    }
    public function testValidateFirstPasswordNull(): void
    {
        $this->resetErrorDto = $this->validateSecondPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNotNull($this->resetErrorDto->emptySecondPW);
        self::assertNull($this->resetErrorDto->secondPWValidationError);
    }

}