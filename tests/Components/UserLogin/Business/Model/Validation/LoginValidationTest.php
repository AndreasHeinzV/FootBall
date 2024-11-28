<?php

declare(strict_types=1);

namespace App\Tests\Components\UserLogin\Business\Model\Validation;

use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\EmailLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\UserAuthentication;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use App\Tests\Fixtures\DatabaseBuilder;
use PHPUnit\Framework\TestCase;

class LoginValidationTest extends TestCase
{

    private UserLoginValidation $loginValidation;

    private UserLoginDto $userLoginDto;

    private DatabaseBuilder $databaseBuilder;

    private ErrorMapper $errorMapper;

    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $sqlConnector = new ORMSqlConnector();
        $this->userLoginDto = new UserLoginDto();
        $userRepository = new UserRepository($sqlConnector, new UserEntityMapper());
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $emailLoginValidation = new EmailLoginValidation();
        $userAuthentication = new UserAuthentication($userBusinessFacade);
        $passwordLoginValidation = new PasswordLoginValidation($userAuthentication);
        $this->errorMapper = new ErrorMapper();
        $this->schemaBuilder = new SchemaBuilder($sqlConnector);
        $this->schemaBuilder->createSchema();

        $this->loginValidation = new UserLoginValidation(
            $this->errorMapper,
            $emailLoginValidation,
            $passwordLoginValidation
        );
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
        parent::tearDown();
    }

    public function testLoginValidation(): void
    {
        $testData = [
            'userId' => 1,
            'firstName' => "testName",
            'lastName' => "dog",
            'email' => "dog@gmail.com",
            'password' => "passw0rd",
        ];

        $this->userLoginDto->email = 'dog@gmail.com';
        $this->userLoginDto->password = 'Passw0rd#';
        $errorsDto = $this->loginValidation->userLoginGetErrorsDTO($this->userLoginDto);
        self::assertInstanceOf(ErrorsDTO::class, $errorsDto);
    }

    public function testValidateNoErrors(): void
    {
        $errorDto = $this->errorMapper->EmptyErrorDto();
        $isError = $this->loginValidation->validateNoErrors($errorDto);
        self::assertTrue($isError);
    }

    public function testValidateNoErrorsWithError(): void
    {
        $errorDto = $this->errorMapper->EmptyErrorDto();
        $errorDto->emailError = 'error';
        $isError = $this->loginValidation->validateNoErrors($errorDto);

        self::assertFalse($isError);
    }


}