<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence\DTO;

class FavoriteDTOList
{
    private array $favoriteDTOs = [];

    /**
     * @return FavoriteDTO[]
     */
    public function getFavoriteDTOs(): array
    {
        return $this->favoriteDTOs;
    }

    public function addFavoriteDTO(FavoriteDTO $favoriteDTO): void
    {
        $this->favoriteDTOs[] = $favoriteDTO;
    }

    public function setFavoriteDTOs(array $favoriteDTOs): void
    {
        foreach ($favoriteDTOs as $favoriteDTO) {
            $this->addFavoriteDTO($favoriteDTO);
        }
    }

}