<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;


#[Entity]
#[Table(name: 'reset_password')]
class ResetPasswordEntity
{

    #[ManyToOne(targetEntity: UserEntity::class, inversedBy: 'tokens')]
    #[Column(name: 'user_id', type: 'integer')]
    //#[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private int $userId;

    #[Id]
    #[Column(type: 'string')]
    private string $actionId;

    #[Column(type: 'string')]
    private int $timestamp;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): ResetPasswordEntity
    {
        $this->userId = $userId;
        return $this;
    }

    public function getActionId(): string
    {
        return $this->actionId;
    }

    public function setActionId(string $actionId): ResetPasswordEntity
    {
        $this->actionId = $actionId;
        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): ResetPasswordEntity
    {
        $this->timestamp = $timestamp;
        return $this;
    }


}