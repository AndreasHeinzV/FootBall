<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'favorites')]
class FavoriteEntity
{


    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $favoriteId;

    #[ORM\Column(type: 'integer')]
    private int $favorite_position;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: UserEntity::class, inversedBy: 'favorites')]
    #[ORM\JoinColumn(name: "user_id_fk", referencedColumnName: "id")]
    private UserEntity $userIdFk;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $teamId;
    #[ORM\Column(type: 'string')]
    private string $team_name;

    #[ORM\Column(type: 'string')]
    private string $team_crest;

    public function getUserId(): UserEntity
    {
        return $this->userIdFk;
    }

    public function getFavoriteId(): int
    {
        return $this->favoriteId;
    }

    public function getFavoritePosition(): int
    {
        return $this->favorite_position;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function getTeamName(): string
    {
        return $this->team_name;
    }

    public function setUser(UserEntity $user): self
    {
        $this->userIdFk = $user;
        return $this;
    }

    public function setTeamId(int $team_id): self
    {
        $this->teamId = $team_id;
        return $this;
    }

    public function setFavoritePosition(int $favorite_position): FavoriteEntity
    {
        $this->favorite_position = $favorite_position;
        return $this;
    }



    public function setTeamName(string $team_name): self
    {
        $this->team_name = $team_name;
        return $this;
    }

    public function getTeamCrest(): string
    {
        return $this->team_crest;
    }

    public function setTeamCrest(string $team_crest): self
    {
        $this->team_crest = $team_crest;
        return $this;
    }

}