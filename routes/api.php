<?php

use App\Http\Controllers\Api\EcosystemController;
use App\Http\Controllers\Api\FilesController;
use App\Http\Controllers\Api\Posts\BookmarkController;
use App\Http\Controllers\Api\Posts\CategoryController;
use App\Http\Controllers\Api\Posts\FilterDataListController;
use App\Http\Controllers\Api\Posts\HorizonDatasetController;
use App\Http\Controllers\Api\Posts\LikeController;
use App\Http\Controllers\Api\Posts\PostController;
use App\Http\Controllers\Api\Posts\SubscriptionController;
use App\Http\Controllers\Api\Posts\ViewController;
use App\Http\Controllers\FastLineController;
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

    Route::get('logout', [SSOAuthController::class, 'logout'])
        ->name('sso.logout');

    Route::get('profile', [JwtAuthController::class, 'userProfile'])
        ->name('user.profile');

    Route::group(['prefix' => 'v1'], static function () {
        Route::group([
            'prefix' => 'fastLine',
        ], static function () {
            Route::get('/', [FastLineController::class, 'list'])
                ->name('fastLine.list');
        }, );

        Route::group(['prefix' => 'ecosystem'], static function () {
            Route::get('/', [EcosystemController::class, 'getEcosystem'])
                ->name('getEcosystem');
        });

        Route::group(['prefix' => 'posts'], static function () {
            Route::get('/', [PostController::class, 'getPosts'])
                ->name('getPosts');

            Route::get('search', [PostController::class, 'searchPosts'])->name('searchPosts');

            Route::group(['prefix' => 'categories'], static function () {
                Route::get('/', [CategoryController::class, 'getCategories'])
                    ->name('getCategories');

                Route::group(['prefix' => 'subscriptions'], static function () {
                    Route::get('/', [SubscriptionController::class, 'getSubscriptions'])
                        ->name('getSubscriptions');
                    Route::post('/', [SubscriptionController::class, 'subscribeToCategory'])
                        ->name('subscribeToCategory');
                });

                Route::get('/{category}', [CategoryController::class, 'getCategory'])
                    ->name('getCategory');
            });

            Route::group(['prefix' => 'bookmarks'], static function () {
                Route::get('/', [BookmarkController::class, 'getBookmarks'])
                    ->name('getBookmarks');
                Route::post('/', [BookmarkController::class, 'bookmarkPost']);
            });

            Route::group(['prefix' => 'views'], static function () {
                Route::get('/', [ViewController::class, 'getViews'])
                    ->name('getViews');
                Route::post('/', [ViewController::class, 'viewPost']);
            });

            Route::group(['prefix' => 'likes'], static function () {
                Route::get('/', [LikeController::class, 'getLikes'])
                    ->name('getLikes');
                Route::post('/', [LikeController::class, 'likePost'])
                    ->name('likePost');
            });

            Route::get('/commonUserData', [PostController::class, 'getPostsUserData'])
                ->name('getPostUserData');

            Route::get('/commonFilterData', [FilterDataListController::class, 'getCommonFilterData'])
                ->name('getCommonFilterData');

            Route::group(['prefix' => 'horizonData'], static function () {
                Route::get('/countries', [FilterDataListController::class, 'getCountries'])
                    ->name('getCountries');

                Route::get('/sectors', [FilterDataListController::class, 'getSectors'])
                    ->name('getSectors');

                Route::get('/authors', [FilterDataListController::class, 'getAuthors'])
                    ->name('getAuthors');

                Route::get('/tickers', [FilterDataListController::class, 'getTickers'])
                    ->name('getTickers');

                Route::get('/isins', [FilterDataListController::class, 'getIsins'])
                    ->name('getIsins');

                Route::get('/{post}', [HorizonDatasetController::class, 'list'])
                    ->name('getHorizonData');
            });

            Route::get('/tags', [FilterDataListController::class, 'getTags'])
                ->name('getTags');

            Route::group(['prefix' => 'files'], static function () {
                Route::get('/{post}', [FilesController::class, 'getPostFiles'])
                    ->name('getPostFiles');
            });

            Route::get('/{post}', [PostController::class, 'getPost'])
                ->name('getPost');
        });
    });
});
