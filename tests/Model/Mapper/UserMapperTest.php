<?php

declare(strict_types=1);

namespace App\Tests\Model\Mapper;


use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

class userMapperTest extends TestCase
{

    private array $testData;

    private UserMapper $userMapper;

    protected function setUp(): void
    {
        $this->testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => 'passw0rd',
        ];
        $this->userMapper = new UserMapper();
    }

    protected function tearDown(): void
    {
        unset($this->testData, $this->userMapper);
        parent::tearDown();
    }

    public function testCreateDTO(): void
    {
        $user = $this->userMapper->createDTO($this->testData);
        assertInstanceOf(UserDTO::class, $user);
        assertSame($user->email, 'dog@gmail.com');
    }

    public function testGetArrayFromDTO(): void
    {
        /**@var \App\Model\DTOs\UserDTO $user */

        $userDTO = $this->userMapper->createDTO($this->testData);
        $userData = $this->userMapper->getUserData($userDTO);

        self::assertArrayHasKey('firstName', $userData);
        assertSame($this->testData['firstName'], $userData['firstName']);
    }
}