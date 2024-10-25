<?php

declare(strict_types=1);

namespace App\Tests\Components\UserRegister\Business\Validation;

use App\Components\UserRegister\Business\Model\ValidationTypesRegister\EmailValidation;
use PHPUnit\Framework\TestCase;

class RegisterEmailValidationTest extends TestCase
{

    private EmailValidation $validation;

    protected function setUp(): void
    {
        $this->validation = new EmailValidation();
    }


    public function testValidationCorrectInput(): void
    {
        $result =$this->validation->validateInput('awge@g.com');
        self::assertNull($result);

    }

    public function testValidationInvalidMail(): void
    {
        $result =$this->validation->validateInput('awge');
        self::assertIsString($result);
        self::assertSame('Invalid email address.', $result);

    }
    public function testValidationEmptyMail(): void
    {
        $result =$this->validation->validateInput('');
        self::assertIsString($result);
        self::assertSame('Email is empty.', $result);

    }
}