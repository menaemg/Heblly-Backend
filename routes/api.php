<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\GratitudeController;

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

// Follow routes
Route::group([
    'middleware' => 'auth:sanctum',
    ],
    function () {
        // Get Followings
        Route::get('/followings', [FollowController::class, 'followings']);
        Route::get('/followings/approved', [FollowController::class, 'approvedFollowings']);
        Route::get('/followings/not-approved', [FollowController::class, 'notApprovedFollowings']);

        // Get Followers
        Route::get('/followers', [FollowController::class, 'followers']);
        Route::get('/followers/approved', [FollowController::class, 'approvedFollowers']);
        Route::get('/followers/not-approved', [FollowController::class, 'notApprovedFollowers']);

        // Follow Actions
        Route::post('/follow/{user}', [FollowController::class, 'follow']);
        Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow']);
        Route::post('/follow/accept/{user}', [FollowController::class, 'acceptFollowRequest']);
        Route::delete('/follow/reject/{user}', [FollowController::class, 'rejectFollowRequest']);
});

// Profile routes
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'profile'
    ],
    function () {
        Route::get('/', [ProfileController::class, 'authProfile']);
        Route::put('/', [ProfileController::class, 'updateProfile']);
        Route::get('/{user}', [ProfileController::class, 'profile']);
        Route::get('/username/{user:username}', [ProfileController::class, 'profileByUsername']);
        Route::put('/{user}/avatar', [ProfileController::class, 'updateAvatar']);
        Route::put('/{user}/cover', [ProfileController::class, 'updateCover']);
});

// Post CRUD routes
Route::get('posts/landing', [PostController::class, 'landingPosts']);
Route::get('posts/friends', [PostController::class, 'friendsPosts'])->middleware('auth:sanctum');
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');


// Gift CRUD routes
Route::apiResource('gifts', GiftController::class)->middleware('auth:sanctum');

// Gratitude CRUD routes
Route::apiResource('gratitudes', GratitudeController::class)->middleware('auth:sanctum');
