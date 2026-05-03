<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorBookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Models\Author;
use Illuminate\Support\Facades\Route;
// =====[ Authentication  {register, login, refresh, me, logout}]====
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


Route::middleware('auth')->group(function () {

    // =========( admin, editor, viewer )==========
    Route::middleware('role:admin,editor,viewer')->group(function () {
        Route::get('books', [BookController::class, 'index']);
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('authors', [AuthorController::class, 'index']);
        Route::get('authors/{id}', [AuthorController::class, 'show']);
        Route::get('books/{id}', [BookController::class, 'show']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);

        Route::prefix('/authors/{author}')->group(function () {
            Route::get('books', [AuthorBookController::class, 'index']);
            Route::post('books', [AuthorBookController::class, 'store']);
            Route::get('books/{book}', [AuthorBookController::class, 'show']);
            Route::patch('books/{book}', [AuthorBookController::class, 'update']);
            Route::delete('books/{book}', [AuthorBookController::class, 'destroy']);
        });
    });


    // =========( admin,editor  )==========
    Route::middleware('role:admin,editor')->group(function () {

        Route::post('books', [BookController::class, 'store']);
        Route::post('authors', [AuthorController::class, 'store']);
        Route::post('categories', [CategoryController::class, 'store']);

        Route::patch('books/{id}', [BookController::class, 'update']);
        Route::patch('authors/{id}', [AuthorController::class, 'update']);
        Route::patch('categories/{id}', [CategoryController::class, 'update']);
    });

    // =========( admin )==========
    Route::middleware('role:admin')->group(function () {
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
        Route::delete('books/{id}', [BookController::class, 'destroy']);
        Route::delete('authors/{id}', [AuthorController::class, 'destroy']);
    });
});