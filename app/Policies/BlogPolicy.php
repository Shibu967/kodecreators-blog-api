<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Blog $blog): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::USER], true);
    }

    public function update(User $user, Blog $blog): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::MAINTAINER], true);
    }

    public function delete(User $user, Blog $blog): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::MAINTAINER], true);
    }
}
