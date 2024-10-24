<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mailcatcher;

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
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class EmailCoordinatorTest extends TestCase
{

    private EmailCoordinator $coordinator;

    private EmailCoordinator $coordinatorNoUser;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        /*
        $sqlConnector = new SqlConnector();
        $userRepository = new UserRepository($sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);
*/
        $sqlConnector = new SqlConnector();
        $userBusinessFacadeMock = $this->createMock(UserBusinessFacadeInterface::class);
        $userDTOStub = new UserDTO(1, 'name', '', '', '');
        $userBusinessFacadeMock->method('getUserByMail')->willReturn($userDTOStub);


        $userBusinessFacadeMockFail = $this->createMock(UserBusinessFacadeInterface::class);
        $userDTOStub2 = new UserDTO(null, 'name', '', '', '');
        $userBusinessFacadeMockFail->method('getUserByMail')->willReturn($userDTOStub2);


        $emailValidation = new EmailValidationPasswordFailed();
        $emailBuilder = new EmailBuilder();
        $emailDispatcher = new EmailDispatcher(new PHPMailer());
        $timeManager = new TimeManager();
        $actionIdGenerator = new ActionIdGenerator();
        // $userPasswordResetEntityManager = new UserPasswordResetEntityManager($sqlConnector);
        $userPasswordResetEntityManagerMock = $this->createMock(UserPasswordResetEntityManager::class);

        $this->coordinator = new EmailCoordinator(
            $emailBuilder,
            $emailDispatcher,
            $emailValidation,
            $timeManager,
            $actionIdGenerator,
            $userPasswordResetEntityManagerMock,
            $userBusinessFacadeMock
        );
        $this->coordinatorNoUser = new EmailCoordinator(
            $emailBuilder,
            $emailDispatcher,
            $emailValidation,
            $timeManager,
            $actionIdGenerator,
            $userPasswordResetEntityManagerMock,
            $userBusinessFacadeMockFail
        );
    }

    public function testSend(): void
    {
        $testEmail = 'test@test.test';
        $status = $this->coordinator->coordinateEmailTransfer($testEmail);
        assertTrue($status);
    }

    public function testSendFailBadMail(): void
    {
        $testEmail = 'gwghw';
        $status = $this->coordinator->coordinateEmailTransfer($testEmail);
        assertFalse($status);
    }

    public function testSendFailNotExistingMail(): void
    {
        $testEmail = 'test@test.test';
        $status = $this->coordinatorNoUser->coordinateEmailTransfer($testEmail);
        assertFalse($status);
    }
}