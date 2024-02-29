<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authentification\LogoutUserController;
use App\Http\Controllers\Api\Authentification\RefreshTokenController;
use App\Http\Controllers\Api\Authentification\RegisterUserController;
use App\Http\Controllers\Api\Authentification\AuthenticateUserController;

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

//User routes group
Route::group(['prefix' => 'user'], function () {
    Route::post('register', RegisterUserController::class)->name('register_user');
    Route::post('login', AuthenticateUserController::class)->name('login_user');
    Route::post('refresh-token',RefreshTokenController::class)->name('refresh_user');
    Route::get('logout', LogoutUserController::class)->name('logout_user');
});
