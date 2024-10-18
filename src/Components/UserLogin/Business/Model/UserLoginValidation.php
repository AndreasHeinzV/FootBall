<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\ErrorMapperInterface;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

readonly class UserLoginValidation implements UserLoginValidationInterface
{
    public function __construct(
        private UserBusinessFacadeInterface $userBusinessFacade,
        private ErrorMapperInterface $errorMapper,
    ) {
    }

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool
    {
        foreach ($errorsDTO as $error) {
            if ($error !== null) {
                return false;
            }
        }
        return true;
    }


    public function userLoginGetErrorsDTO(UserLoginDto $userLoginDto): ErrorsDTO
    {
        $errors['emailError'] = $this->validateEmail($userLoginDto->email);
        $errors['passwordError'] = $this->validatePasswordLogin($userLoginDto);
        return $this->errorMapper->arrayToDto($errors);
    }

    private function validateEmail(string $email): ?string
    {
        if (empty($email)) {
            return "Email is empty.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return null;
    }

    private function validatePasswordLogin($userLoginDto): ?string
    {
        if (empty($userLoginDto->password)) {
            return "Password is empty.";
        }

        if (!$this->validateLogin($userLoginDto)) {
            return 'email or password is wrong';
        }
        return null;
    }

    private function validateLogin(UserLoginDto $userLoginDto): bool
    {
        $userDTOFromDB = $this->userBusinessFacade->getUserByMail($userLoginDto->email);
        return password_verify($userLoginDto->password, $userDTOFromDB->password);
    }
}