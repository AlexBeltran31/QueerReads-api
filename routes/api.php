<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\BookController as AdminBookController;
use App\Http\Controllers\Api\BookController as PublicBookController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/users/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin/test', function (Request $request) {
        return response()->json([
            'message' => 'Admin access granted',
            'user' => $request->user(),
        ]);
    });
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/books', [AdminBookController::class, 'store']);
    Route::get('/admin/books', [AdminBookController::class, 'index']);
});

Route::get('/books', [PublicBookController::class, 'index']);
Route::get('/books/{book}', [PublicBookController::class, 'show']);