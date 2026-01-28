<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/ping', function () {
    return response()->json(['message' => 'API working']);
});

Route::middleware('auth:api')->get('/test-auth', function (Request $request) {
    return response()->json([
        'message' => 'Auth OK',
        'user' => $request->user(),
    ]);
});