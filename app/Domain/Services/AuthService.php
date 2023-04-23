<?php
namespace App\Domain\Services ;

use App\Infrastructure\Persistence\Eloquent\AuthRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request ;
class  AuthService {

    private  $authRepository ;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository ;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        $credentials = $request->only('email', 'password');
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $this->authRepository->login($credentials);
        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }
}
