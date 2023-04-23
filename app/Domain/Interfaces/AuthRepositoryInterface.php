<?php
namespace App\Domain\Interfaces;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function register(Request $request);
    public function login(array $request);
}
