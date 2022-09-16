<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'guest',
    'prefix' => 'auth'
    ],
    function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'auth'
    ],
    function () {
        Route::get('/user', [AuthController::class, 'getAuthUSer']);
        Route::post('/logout', [AuthController::class, 'logout']);
});

Route::fallback(function(){
    return jsonResponse(false, "Page Not Found. If error persists, contact webmaster", null, 404);
});
