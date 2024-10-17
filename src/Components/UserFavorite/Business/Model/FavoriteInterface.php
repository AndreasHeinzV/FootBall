<?php

namespace App\Components\UserFavorite\Business\Model;

interface FavoriteInterface
{
    public function manageFav(array $input): void;
    public function handleRemove(string $teamId): void;
    public function handleAdd( string $teamId): void;
    public function getFavStatus(string $teamId): bool;

}