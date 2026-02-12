<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        return response()->json(
            User::all()
        );
    }

    public function show(User $user) {
        return response()->json($user, 200);
    }
}
