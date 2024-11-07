<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Business\Mailcatcher;

use App\Components\Database\Persistence\SqlConnector;
use App\Components\PasswordReset\Business\Model\PasswordFailed\ActionIdGenerator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailBuilder;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailDispatcher;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailValidationPasswordFailed;
use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class EmailCoordinatorTest extends TestCase
{

    private EmailCoordinator $coordinator;
    private MockObject $userBusinessFacadeMock;
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {

        $this->userBusinessFacadeMock = $this->createMock(UserBusinessFacadeInterface::class);


        $emailValidation = new EmailValidationPasswordFailed();
        $emailBuilder = new EmailBuilder();
        $emailDispatcher = new EmailDispatcher(new PHPMailer());
        $timeManager = new TimeManager();
        $actionIdGenerator = new ActionIdGenerator();

        $userPasswordResetEntityManagerMock = $this->createMock(UserPasswordResetEntityManager::class);

        $this->coordinator = new EmailCoordinator(
            $emailBuilder,
            $emailDispatcher,
            $emailValidation,
            $timeManager,
            $actionIdGenerator,
            $userPasswordResetEntityManagerMock,
            $this->userBusinessFacadeMock
        );
    }

    public function testSend(): void
    {
        $testEmail = 'test@test.test';
        $userDTOStub = new UserDTO(1, 'name', '', '', '');
        $this->userBusinessFacadeMock->method('getUserByMail')->willReturn($userDTOStub);
        $status = $this->coordinator->coordinateEmailTransfer($testEmail);
        assertTrue($status);
    }

    public function testSendFailBadMail(): void
    {
        $testEmail = 'gwghw';
        $userDTOStub = new UserDTO(1, 'name', '', '', '');
        $this->userBusinessFacadeMock->method('getUserByMail')->willReturn($userDTOStub);
        $status = $this->coordinator->coordinateEmailTransfer($testEmail);
        assertFalse($status);
    }

    public function testSendFailNotExistingMail(): void
    {
        $testEmail = 'test@test.test';
        $userDTOStub2 = new UserDTO(null, 'name', '', '', '');
        $this->userBusinessFacadeMock->method('getUserByMail')->willReturn($userDTOStub2);
        $status = $this->coordinator->coordinateEmailTransfer($testEmail);
        assertFalse($status);
    }
}