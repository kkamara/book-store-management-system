<?php

use App\Http\Controllers\V1\Web\BookController;
use App\Http\Controllers\V1\Web\HomeController;
use App\Http\Controllers\V1\Web\OrderBookController;
use App\Http\Controllers\V1\Web\OrderController;
use App\Http\Controllers\V1\Web\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Web\UserController as WebUserController;
use App\Http\Controllers\V1\API\UserController;

Route::prefix('web')
    ->group(function () {
        // Add single page app api routes
        Route::prefix('/user')->group(function () {
            Route::post('/register', [WebUserController::class, 'register']);
            Route::post('/', [WebUserController::class, 'login']);
            Route::delete(
                '/logout',
                [WebUserController::class, 'logout'],
            )->middleware("auth:sanctum");
            Route::get(
                '/authorize',
                [WebUserController::class, 'authorizeUser'],
            )->middleware("auth:sanctum");
        });
        Route::get(
            '/users',
            [WebUserController::class, 'getUsers'],
        )->middleware("auth:sanctum");
        Route::get(
            '/',
            [HomeController::class, 'home'],
        );
        Route::get(
            '/orders',
            [OrderController::class, 'index'],
        )->middleware("auth:sanctum");
        Route::get(
            '/books/search',
            [BookController::class, 'search'],
        );
        Route::get(
            '/books/{slug}',
            [BookController::class, 'get'],
        );
        Route::get(
            '/orders/{referenceNumber}',
            [OrderController::class, 'show'],
        )->middleware("auth:sanctum");
        Route::get(
            '/books/search/editions',
            [BookController::class, 'editions'],
        );
        Route::get(
            '/orders/{referenceNumber}/products',
            [OrderBookController::class, 'index'],
        )->middleware("auth:sanctum");
        Route::get(
            '/books/{slug}/reviews',
            [ReviewController::class, 'getReviewByBook'],
        );
    });

Route::prefix('/user')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/', [UserController::class, 'login'])->name('login');
    Route::delete(
        '/logout',
        [UserController::class, 'logout'],
    )->middleware("auth:sanctum");
    Route::get(
        '/authorize',
        [UserController::class, 'authorizeUser'],
    )->middleware("auth:sanctum");
});
