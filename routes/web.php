<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';

// Route::get('/', function() {
//     return inertia( 'Index',
//         [
//             'name' => 'menaem'
//     ]);
// });

// Route::get('/hello', function() {
//     sleep(2);
//     return inertia( 'Hello',
//         [
//             'name' => 'menaem'
//     ]);
// });

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth:admin');

// Users

// Route::get('users', [UsersController::class, 'index'])
//     ->name('users')
//     ->middleware('auth');

// Route::get('users/create', [UsersController::class, 'create'])
//     ->name('users.create')
//     ->middleware('auth');

// Route::post('users', [UsersController::class, 'store'])
//     ->name('users.store')
//     ->middleware('auth');

// Route::get('users/{user}/edit', [UsersController::class, 'edit'])
//     ->name('users.edit')
//     ->middleware('auth');

// Route::put('users/{user}', [UsersController::class, 'update'])
//     ->name('users.update')
//     ->middleware('auth');

// Route::delete('users/{user}', [UsersController::class, 'destroy'])
//     ->name('users.destroy')
//     ->middleware('auth');

// Route::put('users/{user}/restore', [UsersController::class, 'restore'])
//     ->name('users.restore')
//     ->middleware('auth');

// Organizations

Route::get('users', [UserController::class, 'index'])
    ->name('users')
    ->middleware('auth:admin');

Route::get('users/{user}/edit', [UserController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth:admin');

Route::put('users/{user}', [UserController::class, 'update'])
    ->name('users.update')
    ->middleware('auth:admin');

Route::delete('users/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth:admin');

// Route::get('organizations', [OrganizationsController::class, 'index'])
//     ->name('organizations')
//     ->middleware('auth:admin');

// Route::get('organizations/create', [OrganizationsController::class, 'create'])
//     ->name('organizations.create')
//     ->middleware('auth');

// Route::post('organizations', [OrganizationsController::class, 'store'])
//     ->name('organizations.store')
//     ->middleware('auth');

// Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
//     ->name('organizations.update')
//     ->middleware('auth');

// Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
//     ->name('organizations.destroy')
//     ->middleware('auth');

// Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
//     ->name('organizations.restore')
//     ->middleware('auth');

// Contacts

// Route::get('contacts', [ContactsController::class, 'index'])
//     ->name('contacts')
//     ->middleware('auth');

// Route::get('contacts/create', [ContactsController::class, 'create'])
//     ->name('contacts.create')
//     ->middleware('auth');

// Route::post('contacts', [ContactsController::class, 'store'])
//     ->name('contacts.store')
//     ->middleware('auth');

// Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
//     ->name('contacts.edit')
//     ->middleware('auth');

// Route::put('contacts/{contact}', [ContactsController::class, 'update'])
//     ->name('contacts.update')
//     ->middleware('auth');

// Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
//     ->name('contacts.destroy')
//     ->middleware('auth');

// Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
//     ->name('contacts.restore')
//     ->middleware('auth');

// Reports

// Route::get('reports', [ReportsController::class, 'index'])
//     ->name('reports')
//     ->middleware('auth');

// Images

// Route::get('/img/{path}', [ImagesController::class, 'show'])
//     ->where('path', '.*')
//     ->name('image');

