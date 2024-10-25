<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\RegisterInterface;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;


readonly class UserRegisterBusinessFacade implements UserRegisterBusinessFacadeInterface
{

    public function __construct(
       // private UserBusinessFacadeInterface $userBusinessFacade,
        private RegisterInterface $register
    ) {
    }
/*
    public function registerUser(UserDTO $userDTO): void
    {
        $this->userBusinessFacade->registerUser($userDTO);
    }
*/
    public function registerUserNew(UserRegisterDto $userRegisterDto): ?ErrorsDTO
    {
        return $this->register->execute($userRegisterDto);
    }
}