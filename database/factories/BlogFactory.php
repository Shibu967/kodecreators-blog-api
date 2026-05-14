<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'title'       => fake()->sentence(fake()->numberBetween(4, 8)),
            'description' => fake()->paragraphs(fake()->numberBetween(2, 4), true),
            'image'       => null,
        ];
    }
}
