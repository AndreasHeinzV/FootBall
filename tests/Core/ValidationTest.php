<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\Validation\Validation;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class ValidationTest extends TestCase
{
    private array $testData = [];

    private array $testDataValue;

    public Validation $validation;
    public UserDTO $userDTO;
    public UserMapper $userMapper;

    protected function setUp(): void
    {
        $this->validation = new Validation();
        $this->testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];

        $this->userMapper = new UserMapper();
        $this->userDTO = $this->userMapper->createDTO($this->testData);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->validation, $this->userDTO, $this->userMapper, $this->testData);
        parent::tearDown();
    }

    public function testUserRegisterValidation(): void
    {
        $errorMessage = "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";


        $errorDTO = $this->validation->userRegisterGetErrors($this->userDTO);

        // self::assertInstanceOf(ErrorsDTO::class, $errorDTO);

        self::assertSame($errorDTO->emailError, '');
        self::assertNotEmpty($errorDTO->passwordError);
        self::assertNotSame('', $errorDTO->passwordError);
        self::assertSame($errorMessage, $errorDTO->passwordError);
    }

    public function testUserRegisterValidationAllEmpty(): void
    {
        $errorMessage = "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";

        $userDTO = new UserDTO('', '', '', '');
        $errorDTO = $this->validation->userRegisterGetErrors($userDTO);



        self::assertSame($errorDTO->emailError, 'Email is empty.');
        self::assertSame($errorDTO->passwordError, 'Password is empty.');
        self::assertSame($errorDTO->firstNameEmptyError, 'First name is empty.');
        self::assertSame($errorDTO->lastNameEmptyError, 'Last name is empty.');
    }

    public function testUserRegisterInvalidMail(): void
    {
        $errorMessage = "Invalid email address.";

        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'doggmail.com',
            'password' => 'passw0rd',
        ];
        $userDTO = $this->userMapper->createDTO($testData);
        $errorDTO = $this->validation->userRegisterGetErrors($userDTO);
        self::assertSame($errorMessage, $errorDTO->emailError);
    }

    public function testCheckForNoErrors(): void
    {
        $errorDTO = new ErrorsDTO('', '', '', '', '', '');
        $errorStatus = $this->validation->validateNoErrors($errorDTO);
        assertTrue($errorStatus);
    }

    public function testIfErrorsHasValues(): void
    {
        $errorDTO = new ErrorsDTO('First name is empty.', '', '', '', '', '');
        $errorStatus = $this->validation->validateNoErrors($errorDTO);
        assertFalse($errorStatus);
    }
    /*
    public function testPasswordEmpty(): void
    {
        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'doggmail.com',
            'password' => '',
        ];
        $userDTO = $this->userMapper->createDTO($testData);
        $validation = new Validation();
        $validation->userLoginGetErrors(,$userDTO);
    }
    */
}