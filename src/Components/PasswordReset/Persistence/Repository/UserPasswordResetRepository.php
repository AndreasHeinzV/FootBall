<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\Repository;

use App\Components\Database\Persistence\SqlConnectorInterface;

class UserPasswordResetRepository
{

    public function __construct(private SqlConnectorInterface $sqlConnector)
    {
    }

    public function getPasswordResetActionId(string $email, string $actionId): int|false
    {
        return $this->sqlConnector->querySelect(
            'SELECT action_id FROM reset_passwords WHERE user_email = :user_email AND action_id = :action_id',
            ['user_email' => $email, 'action_id' => $actionId]
        );


    }

}