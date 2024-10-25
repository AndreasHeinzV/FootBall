<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\Register;

use App\Components\UserRegister\Business\Model\ValidationTypesRegister\PasswordValidation;
use PHPUnit\Framework\TestCase;

class RegisterPasswordValidationTest extends TestCase
{
    private PasswordValidation $validation;

    protected function setUp(): void
    {
        $this->validation = new PasswordValidation();
    }


    public function testValidationCorrectInput(): void
    {
        $result = $this->validation->validateInput('Passw0rd#');
        self::assertNull($result);
    }

    public function testValidationInvalidPassword(): void
    {
        $errorMessage = 'Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.';
        $result = $this->validation->validateInput('awge');
        self::assertIsString($result);
        self::assertSame($errorMessage, $result);
    }

    public function testValidationEmptyPassword(): void
    {
        $errorMessage = 'Password is empty.';
        $result = $this->validation->validateInput('');
        self::assertIsString($result);
        self::assertSame($errorMessage, $result);
    }
}