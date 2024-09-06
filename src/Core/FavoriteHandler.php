<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\CompetitionDTO;
use App\Model\DTOs\UserDTO;


class FavoriteHandler
{
    public array $favoritesList = [];

    public UserDTO $userDTO;
    public function __construct(

    ){

    }
    public function addFavorite(UserDTO $userDTO, CompetitionDTO $competitionDTO): void
    {

    $this->favoritesList[$userDTO->email][$competitionDTO->name] = $competitionDTO;

    }

    public function removeFavorite(UserDTO $userDTO, CompetitionDTO $competitionDTO): void
    {


    }

    public function getFavorites(UserDTO $userDTO): array
    {
        return $this->favoritesList[$userDTO->email];
    }


}