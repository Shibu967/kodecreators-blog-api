<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function create(User $user, UserRole $targetRole): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($user->role === UserRole::MAINTAINER) {
            return in_array($targetRole, [UserRole::MAINTAINER, UserRole::USER], true);
        }

        return false;
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
