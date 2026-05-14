<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::USER;
    }

    public function update(User $user, Comment $comment): bool
    {
        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->role === UserRole::MAINTAINER) {
            return true;
        }

        if ($user->role === UserRole::USER) {
            return $user->id === $comment->user_id;
        }

        return false;
    }
}
