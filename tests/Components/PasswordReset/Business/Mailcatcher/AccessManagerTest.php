<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Business\Mailcatcher;

use App\Components\PasswordReset\Business\Model\PasswordReset\AccessManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;

class AccessManagerTest extends TestCase
{


    private AccessManager $accessManager;


    private MockObject $userPasswordResetRepositoryMock;
    protected function setUp(): void
    {
        $array = ['test' => 'test'];
        $this->userPasswordResetRepositoryMock = $this->createMock(UserPasswordResetRepository::class);
        $this->userPasswordResetRepositoryMock->method('getActionIdEntry')->willReturn(false);




        $actionDto = new ActionDTO();

        $actionMapperMock = $this->createMock(ActionMapper::class);
        $actionMapperMock->method('mapArrayToActionDto')->willReturn($actionDto);
        $actionMapper = new ActionMapper();
        $timeManager = new TimeManager();
        $this->accessManager = new AccessManager(
            $this->userPasswordResetRepositoryMock,
            $actionMapper,
            $timeManager
        );


    }

    public function testAccess(): void
    {
        $actionDto = new ActionDto();
        $this->userPasswordResetRepositoryMock->method('getActionIdEntry')->willReturn(false);
        $actionDto->actionId = '3';
        $actionDto->timestamp = 3;
        $result = $this->accessManager->checkForAccess($actionDto);
        assertFalse($result);
    }

    public function testAccessTimestampIsNull(): void
    {
        $actionDto = new ActionDto();
        $actionDto->actionId = '3';
        $this->userPasswordResetRepositoryMock->method('getActionIdEntry')->willReturn(new ActionDTO());
        $result = $this->accessManager->checkForAccess($actionDto);
        assertFalse($result);
    }
}