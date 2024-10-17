<?php

declare(strict_types=1);

namespace App\Tests\Model\EntityManager;

use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\Validation\Validation;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
{
    private string $path;

    private Validation $validation;
    private UserEntityManager $entityManager;
    public UserMapper $userMapper;



    protected function setUp(): void
    {
        $_ENV['test'] = 1;
        $this->path = __DIR__ . '/../../../users_test.json';
        $this->validation = new Validation();
        $this->userMapper = new UserMapper();
        $testData = [
            [
                'firstName' => 'ImATestCat',
                'lastName' => 'JustusCristus',
                'email' => 'dog@gmail.com',
                'password' => 'passw0rd',
            ],
        ];
        file_put_contents($this->path, json_encode($testData));
        $this->entityManager = new UserEntityManager(
            new Validation(),
            new UserRepository(),
            $this->userMapper
        );
        parent::setUp();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    public function testSafeUserSameMail(): void
    {

        $stubRepository = $this->createStub(UserRepository::class);
        $stubRepository->method('getFilePath')
            ->willReturn($this->path);


        $userData = [
            'firstName' => 'testcat',
            'lastName' => 'sghwhwehw',
            'email' => 'dog@gmail.com',
            'password' => 'ewqwh262624rh',
        ];
    //$userDTO = new UserDTO('','','','');

        $beforeSave = json_decode(file_get_contents($stubRepository->getFilePath()), true, 512, JSON_THROW_ON_ERROR);
        self::assertCount(1, $beforeSave);
        $userDTO = $this->userMapper->createDTO($userData);

        $this->entityManager->saveUser($userDTO);
        $actualData = json_decode(file_get_contents($this->path), true, 512, JSON_THROW_ON_ERROR);


        self::assertCount(1, $actualData);
        self::assertSame($userData['firstName'], $userDTO->firstName);
        self::assertSame($userData['lastName'], $userDTO->lastName);
        self::assertSame($userData['email'], $userDTO->email);
        self::assertSame($userData['password'], $userDTO->password);
    }


    public function testUserDifferentMail(): void
    {
        $stubRepository = $this->createStub(UserRepository::class);
        $stubRepository->method('getFilePath')
            ->willReturn($this->path);


        $userData2 = [
            'firstName' => 'test1',
            'lastName' => 'sghwhwehw1',
            'email' => 'catinger@gmail.com',
            'password' => 'ewqwh1262624rh',
        ];
        $userDTO = $this->userMapper->createDTO($userData2);
        $this->entityManager->saveUser($userDTO);
        $actual2Data = json_decode(file_get_contents($stubRepository->getFilePath()), true);

        self::assertCount(2, $actual2Data);
        self::assertSame($userData2['firstName'], $actual2Data[1]['firstName']);
        self::assertSame($userData2['lastName'], $actual2Data[1]['lastName']);
        self::assertSame($userData2['email'], $actual2Data[1]['email']);
        self::assertSame($userData2['password'], $actual2Data[1]['password']);
    }
}
