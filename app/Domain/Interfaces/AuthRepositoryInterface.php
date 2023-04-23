<?php
namespace App\Domain\Interfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator ;

interface AuthRepositoryInterface
{
    public function register(Request $request);
    public function login(array $request);
}
