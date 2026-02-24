<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'pronouns' => [
                'sometimes',
                'string',
                'max:50',
            ],
        ]);

        $user->update($validated);

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }

    public function updatePassword(Request $request, User $user) {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        // Prevent reuse of same password
        if (Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'New password must be different from current password.'
            ], 422);
        }

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.'
        ], 200);
    }
}