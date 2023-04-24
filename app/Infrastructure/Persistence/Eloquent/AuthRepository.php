<?php
namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Dto\UserDTO;
use \App\Domain\Interfaces\AuthRepositoryInterface ;
use App\Domain\Model\User;
use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Validator ;

class AuthRepository implements AuthRepositoryInterface {

    public $validator ;
    public function __construct()
    {

    }

    public function register(Request $request)
    {
        $registrationDTO = UserDTO::fromRegisterRequest($request->all());
        $user = User::create([
            'name' => $registrationDTO->name,
            'email' => $registrationDTO->email,
            'password' => bcrypt($registrationDTO->password)
        ]);

        return $user;
    }

    public function login(array $request)
    {
        $userLoginDto =  UserDTO::fromLoginRequest($request);
       return  auth()->attempt($userLoginDto) ;
    }
}
