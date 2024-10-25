<?php

declare(strict_types=1);

namespace App\Tests\Components\PasswordReset\Business\Mailcatcher;

use App\Components\PasswordReset\Business\Model\PasswordReset\AccessManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;

class AccessManagerTest extends TestCase
{


    private AccessManager $accessManager;

    private AccessManager $accessManagerSecond;

    protected function setUp(): void
    {
        $array = ['test' => 'test'];
        $userPasswordResetRepositoryMock = $this->createMock(UserPasswordResetRepository::class);
        $userPasswordResetRepositoryMock->method('getActionIdEntry')->willReturn(false);

        $userPasswordResetRepositoryMockTwo = $this->createMock(UserPasswordResetRepository::class);
        $userPasswordResetRepositoryMockTwo->method('getActionIdEntry')->willReturn($array);

        $actionDto = new ActionDTO();

        $actionMapperMock = $this->createMock(ActionMapper::class);
        $actionMapperMock->method('mapArrayToActionDto')->willReturn($actionDto);
        $actionMapper = new ActionMapper();
        $timeManager = new TimeManager();
        $this->accessManager = new AccessManager(
            $userPasswordResetRepositoryMock,
            $actionMapper,
            $timeManager
        );

        $this->accessManagerSecond = new AccessManager(
            $userPasswordResetRepositoryMockTwo,
            $actionMapperMock,
            $timeManager
        );
    }

    public function testAccess(): void
    {
        $actionDto = new ActionDto();
        $actionDto->actionId = '3';
        $actionDto->timestamp = 3;
      $result = $this->accessManager->checkForAccess($actionDto);
        assertFalse($result);

    }

    public function testAccessTimestampIsNull(): void
    {
        $actionDto = new ActionDto();
        $actionDto->actionId = '3';

        $result = $this->accessManagerSecond->checkForAccess($actionDto);
        assertFalse($result);
    }

}