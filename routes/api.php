<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController as PublicUserController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\BookController as AdminBookController;
use App\Http\Controllers\Api\BookController as PublicBookController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\CategoryController as PublicCategoryController;
use App\Http\Controllers\Api\ReadingListController;

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
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/test', function (Request $request) {
        return response()->json([
            'message' => 'Admin access granted',
            'user' => $request->user(),
        ]);
    });
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::post('/admin/books', [AdminBookController::class, 'store']);
    Route::get('/admin/books', [AdminBookController::class, 'index']);
    Route::put('/admin/books/{book}', [AdminBookController::class, 'update']);
    Route::delete('/admin/books/{book}', [AdminBookController::class, 'destroy']);
    Route::post('/admin/categories', [AdminCategoryController::class, 'store']);
    Route::get('/admin/categories', [AdminCategoryController::class, 'index']);
    Route::get('/admin/users/{user}', [AdminUserController::class, 'show']);
    Route::put(
        '/admin/categories/{category}',
        [AdminCategoryController::class, 'update']
    );
    Route::delete(
        '/admin/categories/{category}',
        [AdminCategoryController::class, 'destroy']
    );
});

Route::get('/books', [PublicBookController::class, 'index']);
Route::get('/books/{book}', [PublicBookController::class, 'show']);
Route::get('/categories', [PublicCategoryController::class, 'index']);
Route::get(
    '/categories/{category}/books',
    [PublicCategoryController::class, 'books']
);