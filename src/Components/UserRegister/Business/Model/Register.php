<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;
use App\Components\UserRegister\Persistence\Mapper\RegisterMapper;

readonly class Register implements RegisterInterface
{
    public function __construct(
        private UserRegisterValidationInterface $userRegisterValidation,
        private UserBusinessFacadeInterface $userBusinessFacade,
        private RegisterMapper $registerMapper,
    ) {
    }

    public function execute(UserRegisterDto $userRegisterDto): ?ErrorsDTO
    {
        $errorsDTO = $this->userRegisterValidation->userRegisterGetErrorsDTO($userRegisterDto);

        if ($this->userRegisterValidation->validateNoErrors($errorsDTO)) {
            $userRegisterDto->password = password_hash($userRegisterDto->password, PASSWORD_DEFAULT);
            $this->userBusinessFacade->registerUser($this->registerMapper->mapRegisterDtoToUserDTO($userRegisterDto));
            return null;
        }
        return $errorsDTO;
    }
}