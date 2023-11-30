<?php

use App\Http\Controllers\Api\FilesController;
use App\Http\Controllers\Api\Posts\BookmarkController;
use App\Http\Controllers\Api\Posts\CategoryController;
use App\Http\Controllers\Api\Posts\HorizonDatasetController;
use App\Http\Controllers\Api\Posts\LikeController;
use App\Http\Controllers\Api\Posts\PostController;
use App\Http\Controllers\Api\Posts\SubscriptionController;
use App\Http\Controllers\Api\Posts\ViewController;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\SSOAuthController;
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

Route::post('jwt', [JwtAuthController::class, 'login'])
    ->name('jwt.auth');

Route::middleware(['auth.jwt.cookie'])->group(function () {
    Route::get('auth', [SSOAuthController::class, 'user'])
        ->name('sso.auth');
    Route::get('profile', [JwtAuthController::class, 'userProfile'])
        ->name('user.profile');

    Route::group(['prefix' => 'v1'], static function () {
        Route::group(['prefix' => 'posts'], static function () {
            Route::get('/', [PostController::class, 'getPosts'])
                ->name('getPosts');

            Route::group(['prefix' => 'categories'], static function () {
                Route::get('/', [CategoryController::class, 'getCategories'])
                    ->name('getCategories');
                Route::get('/{category}', [CategoryController::class, 'getCategory'])
                    ->name('getCategory');
            });

            Route::group(['prefix' => 'bookmarks'], static function () {
                Route::get('/', [BookmarkController::class, 'getBookmarks'])
                    ->name('getBookmarks');
            });

            Route::group(['prefix' => 'views'], static function () {
                Route::get('/', [ViewController::class, 'getViews'])
                    ->name('getViews');
            });

            Route::group(['prefix' => 'likes'], static function () {
                Route::get('/', [LikeController::class, 'getLikes'])
                    ->name('getLikes');
            });

            Route::group(['prefix' => 'subscriptions'], static function () {
                Route::get('/', [SubscriptionController::class, 'getSubscriptions'])
                    ->name('getSubscriptions');
            });

            Route::group(['prefix' => 'horizonData'], static function () {
                Route::get('/{post}', [HorizonDatasetController::class, 'list'])
                    ->name('getHorizonData');
            });

            Route::group(['prefix' => 'files'], static function () {
                Route::get('/{post}', [FilesController::class, 'getPostFiles'])
                    ->name('getPostFiles');
            });

            Route::get('/{post}', [PostController::class, 'getPost'])
                ->name('getPost');
        });
    });
});
