<?php

declare(strict_types=1);

namespace App\Tests\Components\UserLogin\Business\Model\Validation;

use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\UserAuthentication;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use PHPUnit\Framework\TestCase;


class PasswordValidationTest extends TestCase
{
    private PasswordLoginValidation $validation;

    private DatabaseBusinessFacade $databaseBusinessFacade;

    protected function setUp(): void
    {
        $_ENV['DATABASE'] = 'football_test';
        //----------------------------------------------------------------------------------------
        //DB prepare for use
        $sqlConnector = new SqlConnector();
        $this->databaseBusinessFacade = new DatabaseBusinessFacade(
            new Fixtures($sqlConnector)
        );
        $this->databaseBusinessFacade->createUserTables();
        //----------------------------------------------------------------------------------------
        //create User for Test

        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userRepository = new UserRepository($sqlConnector);
        $userMapper = new UserMapper();
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userEntityManager->saveUser($userMapper->createDTO($testData));

//----------------------------------------------------------------------------------------


        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
        $userAuthentication = new UserAuthentication($userBusinessFacade);
        $this->validation = new PasswordLoginValidation($userAuthentication);

    }

    protected function tearDown(): void{
        $this->databaseBusinessFacade->dropUserTables();
        unset($_ENV);
        parent::tearDown();
    }

    public function testPasswordValidationCorrectData(): void
    {
        $userLoginDto = new UserLoginDto();
        $userLoginDto->email = 'dog@gmail.com';
        $userLoginDto->password = 'passw0rd';

       $status =  $this->validation->validateInput($userLoginDto);
        self::assertNull($status);
    }

    public function testPasswordValidationWrongData(): void
    {
        $userLoginDto = new UserLoginDto();
        $userLoginDto->email = 'wagahh@gwg.de';
        $userLoginDto->password = 'eagewhhw';

        $status =  $this->validation->validateInput($userLoginDto);
        self::assertIsString($status);
        self::assertSame('email or password is wrong', $status);
    }

    public function testPasswordValidationEmptyPassword(): void
    {
        $userLoginDto = new UserLoginDto();
        $userLoginDto->email = 'wagahh@gwg.de';
        $userLoginDto->password = '';

        $status =  $this->validation->validateInput($userLoginDto);
        self::assertIsString($status);
        self::assertSame('Password is empty.', $status);
    }

}