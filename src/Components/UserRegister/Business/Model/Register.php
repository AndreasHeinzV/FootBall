<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

readonly class Register implements RegisterInterface
{
    public function __construct(
        private UserRegisterValidationInterface $userRegisterValidation,
        private UserBusinessFacadeInterface $userBusinessFacade,
    ) {
    }

    public function execute(UserRegisterDto $userRegisterDto): ?ErrorsDTO
    {
        $errorsDTO = $this->userRegisterValidation->userRegisterGetErrorsDTO($userRegisterDto);

        if ($this->userRegisterValidation->validateNoErrors($errorsDTO)) {
            $userRegisterDto->password = password_hash($userRegisterDto->password, PASSWORD_DEFAULT);
            $this->userBusinessFacade->registerUser($this->mapToUserDTO($userRegisterDto));
            return null;
        }
        return $errorsDTO;
    }

//todo maybe change
    public function mapToUserDTO(UserRegisterDto $userRegisterDto): UserDTO
    {
        return new UserDTO(
            null,
            $userRegisterDto->firstName,
            $userRegisterDto->lastName,
            $userRegisterDto->email,
            $userRegisterDto->password
        );
    }
}