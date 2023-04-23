<?php
namespace App\Infrastructure\Persistence\Eloquent;

use \App\Domain\Interfaces\AuthRepositoryInterface ;
use Illuminate\Http\Request ;
class AuthRepository implements AuthRepositoryInterface {

    public function register(Request $request)
    {

    }

    public function login(array $request)
    {
       return  auth()->attempt($request) ;
    }
}
