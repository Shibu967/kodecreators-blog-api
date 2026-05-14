<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class LikeService
{
    /**
     * Toggles a like for the given model and user.
     * Returns true if liked, false if unliked.
     */
    public function toggleLike(Model $likeable, int $userId): bool
    {
        $like = $likeable->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            return false;
        }

        $likeable->likes()->create(['user_id' => $userId]);
        return true;
    }
}
