<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\Repository;

use App\Components\Database\Persistence\SqlConnectorInterface;

readonly class UserPasswordResetRepository implements UserPasswordResetRepositoryInterface
{

    public function __construct(private SqlConnectorInterface $sqlConnector)
    {
    }


    public function getUserIdFromActionId(string $actionId): int|false
    {
        $value =  $this->sqlConnector->querySelect(
            'SELECT user_id FROM reset_passwords WHERE action_id = :action_id', ['action_id' => $actionId]
        );
        return $value['user_id'] ?? false;
    }

    public function getActionIdEntry(string $actionId): array|false
    {
        return $this->sqlConnector->querySelectAll(
            'SELECT user_id, action_id, timestamp FROM reset_passwords WHERE action_id = :action_id',
            ['action_id' => $actionId]
        );
    }

}