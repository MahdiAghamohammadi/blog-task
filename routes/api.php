<?php

use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\UserController;
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

Route::prefix('v1')->namespace('Api\v1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::post('/changePassword', [UserController::class, 'changePassword']);
        });
    });
    Route::prefix('post')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::middleware('auth:api', 'admin')->group(function () {
            Route::post('/store', [PostController::class, 'store']);
            Route::get('/show/{post}', [PostController::class, 'show']);
            Route::put('/update/{post}', [PostController::class, 'update']);
            Route::delete('/destroy/{post}', [PostController::class, 'destroy']);
            Route::get('/status/{post}', [PostController::class, 'status']);
        });
    });
    Route::prefix('comment')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::middleware('auth:api', 'admin')->group(function () {
            Route::post('/store', [CommentController::class, 'store']);
            Route::get('/show/{comment}', [CommentController::class, 'show']);
            Route::put('/update/{comment}', [CommentController::class, 'update']);
            Route::get('/status/{comment}', [CommentController::class, 'status']);
            Route::get('/approved/{comment}', [CommentController::class, 'approved']);
        });
    });
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::middleware('auth:api', 'admin')->group(function () {
            Route::post('/store', [CategoryController::class, 'store']);
            Route::get('/show/{category}', [CategoryController::class, 'show']);
            Route::put('/update/{category}', [CategoryController::class, 'update']);
            Route::delete('/destroy/{category}', [CategoryController::class, 'destroy']);
            Route::get('/status/{category}', [CategoryController::class, 'status']);
        });
    });
});
