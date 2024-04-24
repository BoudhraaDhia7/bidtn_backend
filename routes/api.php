<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Authentification\LogoutUserController;
use App\Http\Controllers\Api\Authentification\RegisterUserController;
use App\Http\Controllers\Api\Authentification\RefreshTokenController;
use App\Http\Controllers\Api\Authentification\AuthenticateUserController;

use App\Http\Controllers\Api\User\GetUserController;
use App\Http\Controllers\Api\User\DeleteUserController;
use App\Http\Controllers\Api\User\BuyJetonPackController;
use App\Http\Controllers\Api\User\ResetPasswordController;
use App\Http\Controllers\Api\User\ForgotPasswordController;
use App\Http\Controllers\Api\User\UpdateDetailUserController;

use App\Http\Controllers\Api\Product\GetProductController;
use App\Http\Controllers\Api\Product\GetProductsController;
use App\Http\Controllers\Api\Product\CreateProductController;
use App\Http\Controllers\Api\Product\DeleteProductController;
use App\Http\Controllers\Api\Product\UpdateProductController;

use App\Http\Controllers\Api\Jetons\ListJetonPackController;
use App\Http\Controllers\Api\Jetons\CreateJetonPackController;

use App\Http\Controllers\Api\Auction\CreateAuction;
use App\Http\Controllers\Api\Auction\GetAuctionsController;

use App\Http\Controllers\Api\Categories\GetCategoriesController;


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

//User Auth routes group
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', RegisterUserController::class)->name('register_user');
    Route::post('login', AuthenticateUserController::class)->name('login_user');
    Route::get('logout', LogoutUserController::class)->name('logout_user');
});

//User Crud route group protected by auth middleware
Route::group(['prefix' => 'users', 'middleware' => 'user.auth'], function () {
    Route::get('    get-auth-user', GetUserController::class)->name('get_auth_user');
    Route::post('buy-jeton-pack', BuyJetonPackController::class)->name('buy_jetons');
    Route::put('update-detail',UpdateDetailUserController::class)->name('update_user');
    Route::delete('{id}', DeleteUserController::class)->name('delete_user');

    /*Route::get('/', 'GetAllUsersController')->name('get_all_users');*/
});

//Token refresh route protected by auth middleware
Route::group(['prefix' => 'token', 'middleware' => 'user.auth'], function () {
    Route::post('refresh-token', RefreshTokenController::class)->name('refresh_token');
});

//Reset password route group
Route::group(['prefix' => 'password'], function () {
    Route::post('forgot', ForgotPasswordController::class)->name('forgot_password');
    Route::post('reset/{resetPasswordToken}',ResetPasswordController::class)->name('reset_password');
});

//Jetons routes
Route::group(['prefix' => 'jetons', 'middleware' => ['user.auth']], function () {
    Route::get('/', ListJetonPackController::class)->name('get_all_jetons');
    Route::post('create', CreateJetonPackController::class)->name('create_jetons_pack');
});

//Product routes
Route::group(['prefix' => 'products', 'middleware' => ['user.auth']], function () {
    Route::post('', CreateProductController::class)->name('create_product');
    Route::get('{id}', GetProductController::class)->name('get_product');
    Route::get('', GetProductsController::class)->name('get_all_products');
    Route::delete('{id}',DeleteProductController::class)->name('delete_product');
    Route::post('{id}',UpdateProductController::class)->name('update_product');
});   

Route::group(['prefix' => 'auction',  'middleware' => 'user.auth'], function () {
    Route::get('/', GetAuctionsController::class)->name('get_all_auctions');
    Route::post('create', CreateAuction::class)->name('create_auction');
    //Route::delete('{id}', 'DeleteAuctionController')->name('delete_auction');
    //Route::put('{id}', 'UpdateAuctionController')->name('update_auction');
});

Route::group(['prefix' => 'categories'] , function(){
    Route::get('/',GetCategoriesController::class)->name('get_all_Categories');
});