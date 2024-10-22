<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validation\Register;

use App\Components\UserRegister\Business\Model\ValidationTypesRegister\LastNameValidation;
use PHPUnit\Framework\TestCase;

class RegisterLastnameValidation extends TestCase
{
    private LastNameValidation $validation;
    protected function setUp(): void{

        $this->validation = new LastNameValidation();
    }

    public function testValidationLastname(): void{

        $result = $this->validation->validateInput('geehsom');
        self::assertNull($result);

    }
    public function testValidationEmptyLastname(): void{

        $result = $this->validation->validateInput('');
        self::assertSame('Lastname is empty.', $result);

    }


}