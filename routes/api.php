<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController as PublicUserController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\CategoryController as PublicCategoryController;
use App\Http\Controllers\Api\ReadingListController;
use App\Http\Controllers\Api\ReviewController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/users/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/users/{user}', [PublicUserController::class, 'update']);
    Route::delete('/users/{user}', [PublicUserController::class, 'destroy']);
    
    Route::get('/reading-list', [ReadingListController::class, 'index']);
    Route::post('/reading-list/{book}', [ReadingListController::class, 'store']);
    Route::put('/reading-list/{book}', [ReadingListController::class, 'update']);
    Route::delete('/reading-list/{book}', [ReadingListController::class, 'destroy']);

    Route::post('/books/{book}/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    Route::get('/books/random-to-read', [BookController::class, 'randomToRead']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {

    // Users management
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{user}', [AdminUserController::class, 'show']);

    // Categories management
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

    // Books management
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);
});

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::get('/categories', [PublicCategoryController::class, 'index']);
Route::get(
    '/categories/{category}/books',
    [PublicCategoryController::class, 'books']
);
Route::get('/books/{book}/reviews', [ReviewController::class, 'index']);