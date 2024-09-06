<?php

declare(strict_types=1);

namespace App\Tests\Model\Repository;



use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class UserRepositoryTest extends TestCase
{
    private string $path;
    protected function setUp(): void{
        $_ENV['test'] = true;
        $this->path = __DIR__ . '/../../../users_test.json';
        $testData = [
            [
                'firstName' => 'ImATestCat',
                'lastName' => 'JustusCristus',
                'email' => 'dog@gmail.com',
                'password' => 'passw0rd',
            ],
        ];
        file_put_contents($this->path, json_encode($testData));
        parent::setUp();
    }
    protected function tearDown(): void{
        unset($_ENV['test']);
        if (file_exists($this->path)) {
            unlink($this->path);
        }
        parent::tearDown();
    }

    public function testFindUserByEmail(): void{

        $testData = [
            [
                'firstName' => 'ImATestCat',
                'lastName' => 'JustusCristus',
                'email' => 'dog@gmail.com',
                'password' => 'passw0rd',
            ],
        ];
        $userRepository = new UserRepository();

        $users = $userRepository->getUsers();


        $username = $userRepository->getUserName($users, $testData[0]['email']);
        $noFoundUser = $userRepository->getUserName($users,'eqwhwhw@g.com');
        self::assertSame($username, $testData[0]['firstName']);
        self::assertSame($noFoundUser, '');

    }

    public function testGetUserFail(): void
    {
        $userRepository = new UserRepository();

        $users = $userRepository->getUsers();
        $userDTO = $userRepository->getUser($users,'bongo@g.com');
            assertSame('', $userDTO->email);

    }
}