<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\Register;

use App\Components\UserRegister\Business\Model\ValidationTypesRegister\FirstNameValidation;
use PHPUnit\Framework\TestCase;

class RegisterFirstnameValidation extends TestCase
{

    private FirstNameValidation $validation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = new FirstNameValidation();
    }

    public function testValidationFirstname(): void{

        $result = $this->validation->validateInput('geehs@g.com');
        self::assertNull($result);

    }
    public function testValidationEmptyFirstname(): void{

        $result = $this->validation->validateInput('');
        self::assertSame('Firstname is empty.', $result);

    }

}