<?php
namespace App\Infrastructure\Persistence\Eloquent;

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

        $user = User::create(array_merge(
            $this->validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return $user;
    }

    public function login(array $request)
    {
       return  auth()->attempt($request) ;
    }
}
