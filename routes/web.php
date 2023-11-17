<?php

use App\Http\Controllers\SSOAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth'], static function () {
    Route::get('/', [SSOAuthController::class, 'redirectToProvider']);
    Route::get('/callback', [SSOAuthController::class, 'handleProviderCallback']);
});