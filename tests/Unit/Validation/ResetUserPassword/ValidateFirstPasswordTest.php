<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\ResetUserPassword;

use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateFirstPassword;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;
use PHPUnit\Framework\TestCase;

class ValidateFirstPasswordTest extends TestCase
{
    private ValidateFirstPassword $validateFirstPassword;
    private ResetErrorDTO $resetErrorDto;

    private ResetDTO $resetDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validateFirstPassword = new ValidateFirstPassword();
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
        $this->resetDTO->FirstPassword = "IlikeCats123#";
        $this->resetErrorDto = $this->validateFirstPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNull($this->resetErrorDto->emptyFirstPW);
        self::assertNull($this->resetErrorDto->firstPWValidationError);

    }

    public function testValidateFirstPasswordEmpty(): void
    {
        $this->resetDTO->FirstPassword = "";
        $this->resetErrorDto = $this->validateFirstPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNotNull($this->resetErrorDto->emptyFirstPW);
        self::assertNull($this->resetErrorDto->firstPWValidationError);

    }

    public function testValidateFirstPasswordIncorrect(): void
    {   $this->resetDTO->FirstPassword = '325fe';
        $this->resetErrorDto = $this->validateFirstPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNull($this->resetErrorDto->emptyFirstPW);
        self::assertNotNull($this->resetErrorDto->firstPWValidationError);

    }
    public function testValidateFirstPasswordNull(): void
    {
        $this->resetErrorDto = $this->validateFirstPassword->validate($this->resetErrorDto, $this->resetDTO);
        self::assertNotNull($this->resetErrorDto->emptyFirstPW);
        self::assertNull($this->resetErrorDto->firstPWValidationError);
    }
}