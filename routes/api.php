<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\SSOAuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth.jwt.cookie'])->group(function () {
    Route::get('auth', [SSOAuthController::class, 'user']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'getCategories']);
        Route::get('/{category}', [CategoryController::class, 'getCategory']);
    });
});