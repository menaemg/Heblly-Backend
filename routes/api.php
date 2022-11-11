<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\HelpController;
use App\Http\Controllers\Api\PickController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\GratitudeController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\ProfileController;
// use App\Http\Controllers\Api\GratitudeController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\WishboardController;

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
Route::get('posts/landing', [PostController::class, 'landingPosts'])->middleware('api');
Route::get('posts/friends', [PostController::class, 'friendsPosts'])->middleware('auth:sanctum');
Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');


// Gift CRUD routes
Route::apiResource('gifts', GiftController::class)->middleware('auth:sanctum');

// Gratitude CRUD routes
Route::apiResource('gratitudes', GratitudeController::class)->middleware('auth:sanctum');

// Wishlist routes
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'wishlist'
    ],
    function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::get('/gratitude', [WishlistController::class, 'indexGratituded']);
        Route::post('/{post}', [WishlistController::class, 'store']);
        Route::delete('/{wish}', [WishlistController::class, 'destroy']);
        Route::put('/{wish}', [WishlistController::class, 'gratitude']);
});

// Wishlist routes
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'wishboard'
    ],
    function () {
        Route::get('/', [WishboardController::class, 'index']);
        Route::post('/add-to-friend/{post}', [WishboardController::class, 'addWishToFriend']);
        Route::post('/add-to-wishlist/{wish}', [WishboardController::class, 'addToWishList']);
        Route::delete('/remove/{wish}', [WishboardController::class, 'destroy']);
});

// Wishlist routes
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'blocklist'
    ],
    function () {
        Route::get('/', [BlockController::class, 'index']);
        Route::post('/block/{user}', [BlockController::class, 'block']);
        Route::delete('/unblock/{user}', [BlockController::class, 'unblock']);
});

Route::post('help/request', [HelpController::class, 'storeRequest'])->middleware('auth:sanctum');
Route::post('help/report', [HelpController::class, 'storeRequest'])->middleware('auth:sanctum');

// Picks
Route::apiResource('picks', PickController::class)->middleware('auth:sanctum');

// Gratitude
Route::apiResource('gratitudes', GratitudeController::class)->middleware('auth:sanctum');

// Friends
Route::get('friends', [FollowController::class, 'friends'])->middleware('auth:sanctum');
Route::get('users', [FollowController::class, 'users'])->middleware('auth:sanctum');
