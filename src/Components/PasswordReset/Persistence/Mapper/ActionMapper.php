<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\Mapper;

use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;

class ActionMapper
{

    public function mapArrayToActionDto(array $actionEntry): ActionDto
    {
        $actionDto = new ActionDto();
        $actionDto->actionId = $actionEntry[0]['action_id'];
        $actionDto->userId = $actionEntry[0]['user_id'];
        $actionDto->timestamp = $actionEntry[0]['timestamp'];
        return $actionDto;
    }

}