<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\UserDTO;


readonly class UserRegisterBusinessFacade implements UserRegisterBusinessFacadeInterface
{

    public function __construct(private UserBusinessFacadeInterface $userBusinessFacade)
    {

    }
    public function registerUser(UserDTO $userDTO): void
    {
        $this->userBusinessFacade->registerUser($userDTO);
    }
}