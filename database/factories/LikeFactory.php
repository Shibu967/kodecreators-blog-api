<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Like>
 */
class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'likeable_id'   => 1,
            'likeable_type' => 'App\Models\Blog',
        ];
    }
}
