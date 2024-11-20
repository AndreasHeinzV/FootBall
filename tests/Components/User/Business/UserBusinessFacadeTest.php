<?php

declare(strict_types=1);

namespace App\Tests\Components\User\Business;

use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use PHPUnit\Framework\TestCase;

class UserBusinessFacadeTest extends TestCase
{

    private UserBusinessFacade $userBusinessFacade;

    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['DATABASE'] = 'football_test';
        $sqlConnector = new ORMSqlConnector();
        $userRepository = new UserRepository($sqlConnector, new UserEntityMapper());
        $userEntityManager = new UserEntityManager($sqlConnector);
        $this->userBusinessFacade = new userBusinessFacade($userRepository, $userEntityManager);

        $this->schemaBuilder = new SchemaBuilder($sqlConnector);
        $this->schemaBuilder->createSchema();

        $testData = [
            'userId' => -1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userEntityManager->saveUser((new UserMapper())->createDTO($testData));
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->dropSchema();
        parent::tearDown();
    }

    public function testGetUser(): void
    {
        $userDTO = $this->userBusinessFacade->getUserByMail('dog@gmail.com');

        self::assertSame('dog@gmail.com', $userDTO->email);
        self::assertNotSame(-1, $userDTO->userId);
    }
    public function testGetUserFailed(): void
    {

        $userDTO = $this->userBusinessFacade->getUserByMail('dog@gmail.com');

        self::assertSame('dog@gmail.com', $userDTO->email);
        self::assertNotSame(-1, $userDTO->userId);
    }

    public function testGetUserNoMailFound(): void
    {
        $userDTO = $this->userBusinessFacade->getUserByMail('cat@gmail.com');
        self::assertNull($userDTO->userId);
        self::assertEmpty($userDTO->firstName);
    }

    public function testGetUserById(): void
    {
        $testData = [
            'userId' => -1,
            'firstName' => '',
            'lastName' => '',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];

        $userId = $this->userBusinessFacade->getUserIdByMail((new UserMapper())->createDTO($testData));
        self::assertNotEmpty($userId);
        self::assertSame(1, $userId);
    }

    public function testGetUserByIdFail(): void
    {
        $testData = [
            'userId' => -1,
            'firstName' => '',
            'lastName' => '',
            'email' => 'cat@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];

        $userId = $this->userBusinessFacade->getUserIdByMail((new UserMapper())->createDTO($testData));
        self::assertFalse($userId);
    }


    public function testGetUsers(): void
    {
        $users = $this->userBusinessFacade->getUsers();

        self::assertNotEmpty($users);
        self::assertCount(1, $users);
    }

    public function testGetUsersButEmptyDb(): void
    {
        $this->schemaBuilder->dropSchema();
        $this->schemaBuilder->createSchema();

        $users = $this->userBusinessFacade->getUsers();

        self::assertEmpty($users);
    }

    public function testRegisterUser(): void
    {
        $testData = [
            'userId' => -1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'cat@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];

        $this->userBusinessFacade->registerUser((new UserMapper())->createDTO($testData));
        $users = $this->userBusinessFacade->getUsers();
        $usersId = $this->userBusinessFacade->getUserIdByMail((new UserMapper())->createDTO($testData));
        self::assertCount(2, $users);
        self::assertSame(2, $usersId);


    }

    public function testUpdateUserInfo(): void
    {
        $testData = [
            'userId' => -1,
            'firstName' => 'testFname',
            'lastName' => 'testName',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];

        $oldUserDTO = $this->userBusinessFacade->getUserByMail('dog@gmail.com');
        $this->userBusinessFacade->registerUser((new UserMapper())->createDTO($testData));
        $newUserDTO = $this->userBusinessFacade->getUserByMail('dog@gmail.com');

        self::assertSame($oldUserDTO->email, $newUserDTO->email);
        self::assertNotSame($newUserDTO->firstName, $oldUserDTO->firstName);
    }
}