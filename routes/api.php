<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HelpController;
use App\Http\Controllers\Api\PickController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\PostWishController;
use App\Http\Controllers\Api\BlockController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\GratitudeController;
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
// Route::apiResource('gratitudes', GratitudeController::class)->middleware('auth:sanctum');

// Wishlist routes
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'wish-list'
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
        // Route::get('/', [WishboardController::class, 'index']);
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
Route::get('gratitudes', [App\Http\Controllers\GratitudeController::class, 'index'])->middleware('auth:sanctum');
Route::post('gratitudes', [App\Http\Controllers\GratitudeController::class, 'store'])->middleware('auth:sanctum');
Route::put('gratitudes/{gratitude}', [App\Http\Controllers\GratitudeController::class, 'update'])->middleware('auth:sanctum');
Route::get('gratitudes/{gratitude}', [App\Http\Controllers\GratitudeController::class, 'show'])->middleware('auth:sanctum');
Route::delete('gratitudes/{gratitude}', [App\Http\Controllers\GratitudeController::class, 'destroy'])->middleware('auth:sanctum');


// Wish List
Route::post('wishlist', [PostWishController::class, 'store'])->middleware('auth:sanctum');
Route::get('wishlist', [PostWishController::class, 'index'])->middleware('auth:sanctum');
Route::get('wishlist/{wish}', [PostWishController::class, 'show'])->middleware('auth:sanctum');
Route::put('wishlist/{wish}', [PostWishController::class, 'update'])->middleware('auth:sanctum');
Route::delete('wishlist/{wish}', [PostWishController::class, 'destroy'])->middleware('auth:sanctum');

// Gift
Route::post('gifts', [GiftController::class, 'store'])->middleware('auth:sanctum');
Route::get('giftlist', [GiftController::class, 'index'])->middleware('auth:sanctum');
Route::get('gifts/{gift}', [GiftController::class, 'show'])->middleware('auth:sanctum');
Route::put('gifts/{gift}', [GiftController::class, 'update'])->middleware('auth:sanctum');
Route::delete('gifts/{gift}', [GiftController::class, 'destroy'])->middleware('auth:sanctum');

// Board
Route::get('wishboard', [BoardController::class, 'index'])->middleware('auth:sanctum');
Route::post('board', [BoardController::class, 'store'])->middleware('auth:sanctum');
Route::get('board/{board}', [BoardController::class, 'show'])->middleware('auth:sanctum');
Route::put('board/{board}', [BoardController::class, 'update'])->middleware('auth:sanctum');
Route::delete('board/{board}', [BoardController::class, 'destroy'])->middleware('auth:sanctum');

// Friends
Route::get('friends', [FollowController::class, 'friends'])->middleware('auth:sanctum');
Route::get('users', [FollowController::class, 'users'])->middleware('auth:sanctum');

// Settings

Route::get('settings/notification-status', [SettingController::class, 'notificationStatus'])->middleware('auth:sanctum');
Route::put('settings/notification-status', [SettingController::class, 'updateNotification'])->middleware('auth:sanctum');

Route::get('settings/privacy-status', [SettingController::class, 'privacyStatus'])->middleware('auth:sanctum');
Route::put('settings/privacy-status', [SettingController::class, 'updatePrivacy'])->middleware('auth:sanctum');

Route::delete('settings/delete-account', [SettingController::class, 'deleteAccount'])->middleware('auth:sanctum');

// comments

Route::get('post/{post}/comment', [CommentController::class, 'index'])->middleware('auth:sanctum');
Route::post('post/{post}/comment', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::delete('post/{post}/comment/{commentId}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');


// Notifications
Route::get('notifications', [NotificationController::class, 'index'])->middleware('auth:sanctum');
Route::get('notifications/gift', [NotificationController::class, 'indexGift'])->middleware('auth:sanctum');
Route::patch('notifications/read/{id}', [NotificationController::class, 'read'])->middleware('auth:sanctum');
Route::patch('notifications/read-all', [NotificationController::class, 'readAll'])->middleware('auth:sanctum');
Route::patch('notifications/read-all/gift', [NotificationController::class, 'readAllGift'])->middleware('auth:sanctum');
Route::patch('notifications/unread/{id}', [NotificationController::class, 'unRead'])->middleware('auth:sanctum');
Route::delete('notifications/delete/{id}', [NotificationController::class, 'destroy'])->middleware('auth:sanctum');

// Likes
Route::post('post/{post}/like', [LikeController::class, 'like'])->middleware('auth:sanctum');
Route::delete('post/{post}/unlike', [LikeController::class, 'unlike'])->middleware('auth:sanctum');
Route::post('post/{post}/toggle-like/', [LikeController::class, 'toggleLike'])->middleware('auth:sanctum');
Route::get('post/{post}/likes-count', [LikeController::class, 'postLikesCount'])->middleware('auth:sanctum');
Route::get('post/{post}/is-liked', [LikeController::class, 'hasLiked'])->middleware('auth:sanctum');
Route::get('user/likes-count', [LikeController::class, 'userLikesCount'])->middleware('auth:sanctum');


