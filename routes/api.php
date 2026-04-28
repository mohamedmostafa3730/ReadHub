<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorBookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// =========={Author}==========
// Route::apiResource('author', AuthorController::class);
// // =========={Book With Author}==========
// Route::prefix('/authors/{author}')->group(function () {
//     Route::get('books', [AuthorBookController::class, 'index']);
//     Route::post('books', [AuthorBookController::class, 'store']);
//     Route::get('books/{book}', [AuthorBookController::class, 'show']);
//     Route::patch('books/{book}', [AuthorBookController::class, 'update']);
//     Route::delete('books/{book}', [AuthorBookController::class, 'destroy']);
// });
// // =========={Auth [login,me,refresh,logout] }==========
// Route::prefix('auth')->group(function () {
//     Route::post('register', [AuthController::class, 'register']);
//     Route::post('login', [AuthController::class, 'login']);
//     Route::middleware('auth:api')->group(function () {

//         // =========={Book}==========
//         Route::apiResource('book', BookController::class);


//         Route::get('me', [AuthController::class, 'me']);
//         Route::post('refresh', [AuthController::class, 'refresh']);
//         Route::post('logout', [AuthController::class, 'logout']);
//     });
// });

// ===========================================

// =====[task 1: Author {CRUD}]====
// Route::apiResource('books', BookController::class);
// =====[task 2: Part (1) => Author {CRUD}]====
Route::apiResource('authors', AuthorController::class);

// =====[task 2: Part (2) => Author With Book  {CRUD}]====
Route::prefix('/authors/{author}')->group(function () {
    Route::get('books', [AuthorBookController::class, 'index']);
    Route::post('books', [AuthorBookController::class, 'store']);
    Route::get('books/{book}', [AuthorBookController::class, 'show']);
    Route::patch('books/{book}', [AuthorBookController::class, 'update']);
    Route::delete('books/{book}', [AuthorBookController::class, 'destroy']);
});
// __________________{Add book inside middleware {auth:api} }__________________
// =====[task 3: Part (1) => Authentication  {register, login, refresh, me, logout}]====
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


// =====[task 3: Part (2) => {Authentication CRUD operations}]====
Route::middleware('auth:api')->group(function () {
    Route::apiResource('books', BookController::class);
});


// =====[task 4: Part (1) => {category CRUD}]====
// =====[task 5: {Authentication & Authorization => roles and permissions}]====
Route::middleware('auth')->group(function () {

    // =========( admin, editor, viewer )==========
    Route::middleware('role:admin,editor,viewer')->group(function () {
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);
    });


    // =========( admin,editor  )==========
    Route::middleware('role:admin,editor')->group(function () {
        Route::post('categories', [CategoryController::class, 'store']);
        Route::patch('categories/{id}', [CategoryController::class, 'update']);
    });

    // =========( admin )==========
    Route::middleware('role:admin')->group(function () {
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    });
});