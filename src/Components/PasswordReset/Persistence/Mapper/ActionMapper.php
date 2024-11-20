<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\Mapper;

use App\Components\Database\Persistence\Entity\ResetPasswordEntity;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;

class ActionMapper
{
/*
    public function mapArrayToActionDto(array $actionEntry): ActionDto
    {
        $actionDto = new ActionDto();
        $actionDto->actionId = $actionEntry[0]['action_id'];
        $actionDto->userId = $actionEntry[0]['user_id'];
        $actionDto->timestamp = $actionEntry[0]['timestamp'];
        return $actionDto;
    }
*/
    public function mapEntityToActionDto(ResetPasswordEntity $resetPasswordEntity): ActionDto{
        $actionDto = new ActionDto();
        $actionDto->userId = $resetPasswordEntity->getUserId();
        $actionDto->timestamp = $resetPasswordEntity->getTimestamp();
        $actionDto->actionId = $resetPasswordEntity->getActionId();
        return $actionDto;
    }
}