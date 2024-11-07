<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Business;


use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Business\Model\FavoriteInterface;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;
use App\Components\UserFavorite\Persistence\UserFavoriteRepositoryInterface;

readonly class UserFavoriteBusinessFacade implements UserFavoriteBusinessFacadeInterface
{
    public function __construct(
        private FavoriteInterface $favorite,
        private UserFavoriteRepositoryInterface $userFavoriteRepository
    ) {
    }

    /**
     * @@param array{add?: string, delete?: string, up?: string, down?: string} $input
     * @return void
     */
    public function manageFavoriteInput(array $input): void
    {
        $this->favorite->manageFav($input);
    }

    public function getFavoriteStatus(string $teamId): bool
    {
        return $this->favorite->getFavStatus($teamId);
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        return $this->userFavoriteRepository->getUserFavorites($userDTO);
    }
}