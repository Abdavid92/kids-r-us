<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ProfileInformationController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ProfitsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', RegisterController::class)
    ->name('register');

Route::post('/login', AuthenticationController::class)
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {

    //Passwords
    Route::post('/password-update', [PasswordController::class, 'update'])
        ->name('password-update');

    //Users
    Route::get('/user', [UserController::class, 'user'])->name('user');

    Route::put('/user/profile-information', ProfileInformationController::class)
        ->name('user.profile-information');

    Route::get('/users', [UserController::class, 'index'])
        ->name('users');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

    Route::patch('/users/{user}/assign-role', [UserController::class, 'assignRole'])
        ->name('users.assign-role');

    Route::patch('/users/{user}/remove-role', [UserController::class, 'removeRole'])
        ->name('users.remove-role');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    //ProfilePhotos
    Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
        ->name('current-profile-photo.destroy');

    //Categories
    Route::apiResource('categories', CategoryController::class);

    //Products
    Route::get('/products/out-stock', [ProductController::class, 'outStock'])
        ->name('products.out-stock');

    Route::get('/products/search', [SearchProductController::class, 'search'])
        ->name('products.search');

    Route::get('/products/search-count', [SearchProductController::class, 'searchCount'])
        ->name('products.search-count');

    Route::post('/products/photo/{product}', [ProductPhotoController::class, 'store'])
        ->name('products.store-photo');

    Route::delete('/products/photo/{photo}', [ProductPhotoController::class, 'destroy'])
        ->name('products.destroy-photo');

    Route::apiResource('products', ProductController::class);

    //Reviews
    Route::get('/reviews/{product}', [ReviewController::class, 'index'])
        ->name('reviews.index');

    Route::apiResource('reviews', ReviewController::class)
        ->except(['index', 'show']);

    Route::get('/reviews/{product}/for-product', [ReviewController::class, 'forProduct'])
        ->name('reviews.for-product');

    //Sales
    Route::get('/sales', [PurchaseController::class, 'sales'])
        ->name('sales');

    Route::post('/buy', [PurchaseController::class, 'buy'])
        ->name('buy');

    //Profits
    Route::get('/profits', [ProfitsController::class, 'profits'])
        ->name('profits');
});
