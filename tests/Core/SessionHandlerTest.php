<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Core\SessionHandler;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class SessionHandlerTest extends TestCase
{
    public UserMapper $userMapper;

    public array $testData = [];

    protected function setUp(): void
    {
        session_start();
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
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        unset($this->userMapper);
        parent::tearDown();
    }

    public function testSessionHandlerNoSession(): void
    {
        $sessionHandler = new SessionHandler($this->userMapper);
        $userDTO = $sessionHandler->getUserDTO();

        assertSame('', $userDTO->email);
        assertSame('', $userDTO->password);
    }

    public function testSessionHandler(): void
    {
        session_start();
        $sessionHandler = new SessionHandler($this->userMapper);
        $sessionHandler->setUserDTO($this->userMapper->createDTO($this->testData));
        $userDTO = $sessionHandler->getUserDTO();

        assertSame($this->testData['firstName'], $userDTO->firstName);
    }
}