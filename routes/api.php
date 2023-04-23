<?php

//use App\Http\Controllers\Api\AuthController;
use App\Infrastructure\Controllers\Api\AuthController ;
use App\Infrastructure\Controllers\Api\RequestCreditsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RequestCreditController ;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// crée une demande
//Route::post("requestCredits/create",[ RequestCreditController::class ,'store']);

Route::post("requestCredits/create",[ RequestCreditsController::class ,'store']);

//liste des demandes
//Route::get("requestCredits",[ RequestCreditController::class ,'findAll'])->middleware('auth.jwt');

Route::get('requestCredits',[RequestCreditsController::class,'findAll']);

// register and login
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();
});

