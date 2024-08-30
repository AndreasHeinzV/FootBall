<?php

declare(strict_types=1);

namespace App\Tests\Model\EntityManager;

use App\Core\Validation;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
{
    private string $path;

    private Validation $validation;
    private UserEntityManager $entityManager;

    protected function setUp(): void
    {
        $this->path = __DIR__ . '/../../../users_test.json';
        $this->validation = new Validation();

        $testData = [
            [
                'firstName' => 'ImATestCat',
                'lastName' => 'JustusCristus',
                'email' => 'dog@gmail.com',
                'password' => 'passw0rd',
            ],
        ];
        file_put_contents($this->path, json_encode($testData));



        $this->entityManager = new UserEntityManager($this->validation, new UserRepository());
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


        $beforeSave = json_decode(file_get_contents($stubRepository->getFilePath()), true, 512, JSON_THROW_ON_ERROR);
        self::assertCount(1, $beforeSave);
        $this->entityManager->save($userData);
        $actualData = json_decode(file_get_contents($this->path), true, 512, JSON_THROW_ON_ERROR);


        self::assertCount(1, $actualData);
        self::assertSame($userData['firstName'], $actualData[0]['firstName']);
        self::assertSame($userData['lastName'], $actualData[0]['lastName']);
        self::assertSame($userData['email'], $actualData[0]['email']);
        self::assertSame($userData['password'], $actualData[0]['password']);


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

        $this->entityManager->save($userData2);
        $actual2Data = json_decode(file_get_contents($stubRepository->getFilePath()), true);

        self::assertCount(2, $actual2Data);
        self::assertSame($userData2['firstName'], $actual2Data[1]['firstName']);
        self::assertSame($userData2['lastName'], $actual2Data[1]['lastName']);
        self::assertSame($userData2['email'], $actual2Data[1]['email']);
        self::assertSame($userData2['password'], $actual2Data[1]['password']);
    }
}
