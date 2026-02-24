<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $authUser, User $user): bool
    {
        return $authUser->role === 'admin'
            && $authUser->id !== $user->id;
    }
}