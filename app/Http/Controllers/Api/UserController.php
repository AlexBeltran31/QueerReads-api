<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function update(Request $request, User $user) {
        $authUser = $request->user();

        if ($authUser->id !== $user->id && $authUser->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json($user, 200);
    }

    public function destroy(User $user) {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }
}
