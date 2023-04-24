<?php

namespace App\Domain\Dto;
use App\Domain\Model\User;

class UserDTO
{
    public string $name;
    public string $email;
    public string $password;

    public static function fromRegisterRequest(array $request): self
    {
        $dto = new self();
        $dto->name = $request['name'] ?? '';
        $dto->email = $request['email'] ?? '';
        $dto->password = $request['password'] ?? '';

        return $dto;
    }

    public static function fromLoginRequest($request)
    {
        $dto = new self();
        $dto->email = $request['email'];
        $dto->password = $request['password'];
        return ["email"=> $dto->email , "password" =>$dto->password ];
    }
    public static function fromModel(User $user): self
    {
        $dto = new self();
        $dto->name = $user->name;
        $dto->email = $user->email;
        return $dto;
    }
}
