<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'blog_id'     => Blog::factory(),
            'user_id'     => User::factory(),
            'description' => fake()->paragraph(fake()->numberBetween(1, 3)),
        ];
    }
}
