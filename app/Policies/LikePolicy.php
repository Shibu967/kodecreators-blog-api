<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Like;
use App\Models\User;

class LikePolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::USER;
    }

    public function delete(User $user, Like $like): bool
    {
        return $user->role === UserRole::USER && $user->id === $like->user_id;
    }
}
