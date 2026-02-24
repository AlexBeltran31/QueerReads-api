<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->role === 'admin';
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->role === 'admin'
            && $authUser->id !== $user->id;
    }

    public function manageReading(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }
}