<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\EntityManager;

use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\User\Persistence\DTOs\UserDTO;

readonly class UserPasswordResetEntityManager
{

    public function __construct(private SqlConnectorInterface $sqlConnector)
    {
    }

    public function savePasswordResetAction(UserDTO $userDTO, MailDTO $mailDTO): void
    {
        $this->sqlConnector->queryInsert(
            'INSERT INTO reset_passwords(user_id,action_id, user_email, timestamp) Values(:user_id,:action_id,:user_email,:timestamp)'
            ,[
                'user_id' => $userDTO->userId,
                'action_id' => $mailDTO->actionId,
                'user_email' => $userDTO->email,
                'timestamp' => $mailDTO->timestamp,
            ]
        );
    }
}