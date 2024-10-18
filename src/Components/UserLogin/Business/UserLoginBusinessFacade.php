<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\UserRepositoryInterface;
use App\Components\UserLogin\Business\Model\LoginInterface;
use App\Components\UserLogin\Business\Model\UserLoginValidationInterface;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

readonly class UserLoginBusinessFacade implements UserLoginBusinessFacadeInterface
{

    public function __construct(
        private LoginInterface $login
    ) {
    }

    public function loginUser(UserLoginDto $userLoginDto): ?ErrorsDTO
    {
        // TODO: Implement loginUser() method.
        return $this->login->execute($userLoginDto);
    }
}