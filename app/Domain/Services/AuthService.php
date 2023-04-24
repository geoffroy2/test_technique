<?php
namespace App\Domain\Services ;

use App\Domain\Exceptions\CustomException;
use App\Infrastructure\Persistence\Eloquent\AuthRepository;
use Illuminate\Support\Facades\Validator ;
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
          //  return response()->json($validator->errors(), 422);
            throw new CustomException($validator->errors(),422)  ;
        }

        $token = $this->authRepository->login($credentials);
        if (! $token) {
            throw new CustomException('Unauthorized',401) ;
        }
        return $token;
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            throw new CustomException($validator->errors()->toJson(),400);
        }
        $this->authRepository->validator = $validator ;
        $user = $this->authRepository->register($request);
        return $user ;
    }
}
