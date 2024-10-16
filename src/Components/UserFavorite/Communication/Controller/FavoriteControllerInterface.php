<?php

namespace App\Components\UserFavorite\Communication\Controller;

use App\Core\ViewInterface;

interface FavoriteControllerInterface
{
    public function load(ViewInterface $view);
}