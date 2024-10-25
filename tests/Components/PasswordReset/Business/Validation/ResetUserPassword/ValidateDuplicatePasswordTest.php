<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Business\Validation\ResetUserPassword;

use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateDuplicatePassword;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;
use PHPUnit\Framework\TestCase;

class ValidateDuplicatePasswordTest extends TestCase
{

    private ResetErrorDTO $resetErrorDTO;

    private ResetDTO $resetDTO;
    private ValidateDuplicatePassword $validateDuplicatePassword;


    protected function setUp(): void
    {
        parent::setUp();

        $this->resetErrorDTO = new ResetErrorDTO();
        $this->resetDTO = new ResetDTO();
        $this->validateDuplicatePassword = new ValidateDuplicatePassword();
    }

    public function testValidate(): void
    {
        $this->resetDTO->FirstPassword = "test";
        $this->resetDTO->SecondPassword = "test";

        $this->resetErrorDTO = $this->validateDuplicatePassword->validate($this->resetErrorDTO, $this->resetDTO);

        self::assertNull($this->resetErrorDTO->differentPWerror);
    }

    public function testValidateDifferentInput(): void
    {
        $this->resetDTO->FirstPassword = "test";
        $this->resetDTO->SecondPassword = "tes";

        $this->resetErrorDTO = $this->validateDuplicatePassword->validate($this->resetErrorDTO, $this->resetDTO);

        self::assertNotNull($this->resetErrorDTO->differentPWerror);
        self::assertIsString($this->resetErrorDTO->differentPWerror);
    }

    public function testValidateOneInputNull(): void
    {
        $this->resetDTO->FirstPassword = "test";


        $this->resetErrorDTO = $this->validateDuplicatePassword->validate($this->resetErrorDTO, $this->resetDTO);

        self::assertNotNull($this->resetErrorDTO->differentPWerror);
        self::assertIsString($this->resetErrorDTO->differentPWerror);
    }
    public function testValidateBothNull(): void
    {
        $this->resetErrorDTO = $this->validateDuplicatePassword->validate($this->resetErrorDTO, $this->resetDTO);
        self::assertNull($this->resetErrorDTO->differentPWerror);

    }

}