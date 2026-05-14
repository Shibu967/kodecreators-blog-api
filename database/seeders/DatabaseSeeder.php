<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Total:
     *  - 500 users
     *  - 1000 blogs
     *  - 10,000 comments (10 per blog)
     *  - 10,000 blog likes (10 per blog)
     */
    public function run(): void
    {
        // Disable query log to save memory
        DB::disableQueryLog();

        $this->command->info('Seeding users...');
        $this->seedUsers();

        $this->command->info('Seeding blogs...');
        $this->seedBlogs();

        $this->command->info('Seeding comments & likes...');
        $this->seedCommentsAndLikes();

        $this->command->info('Done! Database seeded successfully.');
    }

    // ─── Users ────────────────────────────────────────────────

    private function seedUsers(): void
    {
        // 1 guaranteed admin for testing
        User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
            'role'  => UserRole::ADMIN->value,
        ]);

        // 499 random users with weighted roles (80% user, 15% maintainer, 5% admin)
        User::factory(499)->create();

        $this->command->info('  ✓ 500 users created');
    }

    // ─── Blogs ────────────────────────────────────────────────

    private function seedBlogs(): void
    {
        // Only User-role users can create blogs
        $userIds = User::where('role', UserRole::USER->value)->pluck('id');

        // Create 1000 blogs in chunks of 100 to avoid memory spikes
        collect(range(1, 1000))->chunk(100)->each(function (Collection $chunk) use ($userIds) {
            $blogs = $chunk->map(fn() => [
                'user_id'     => $userIds->random(),
                'title'       => fake()->sentence(fake()->numberBetween(4, 8)),
                'description' => fake()->paragraphs(fake()->numberBetween(2, 4), true),
                'image'       => null,
                'created_at'  => fake()->dateTimeBetween('-6 months', 'now'),
                'updated_at'  => now(),
            ])->toArray();

            DB::table('blogs')->insert($blogs);
        });

        $this->command->info('  ✓ 1000 blogs created');
    }

    // ─── Comments & Likes ─────────────────────────────────────

    private function seedCommentsAndLikes(): void
    {
        // All user IDs for likes/comments (users can like and comment)
        $userIds = User::where('role', UserRole::USER->value)->pluck('id');

        $commentRows = [];
        $likeRows    = [];

        // Process blogs in chunks of 100 to avoid loading 1000 blog IDs at once in a loop
        Blog::select('id')->chunk(100, function (Collection $blogs) use ($userIds, &$commentRows, &$likeRows) {
            foreach ($blogs as $blog) {
                // Shuffle users to pick random unique ones for likes
                $sampledUsers = $userIds->shuffle()->take(10);

                // 10 comments per blog
                foreach ($sampledUsers as $userId) {
                    $commentRows[] = [
                        'blog_id'     => $blog->id,
                        'user_id'     => $userId,
                        'description' => fake()->paragraph(fake()->numberBetween(1, 3)),
                        'created_at'  => fake()->dateTimeBetween('-3 months', 'now'),
                        'updated_at'  => now(),
                    ];
                }

                // 10 likes per blog (unique per user to prevent duplicates)
                foreach ($sampledUsers as $userId) {
                    $likeRows[] = [
                        'user_id'       => $userId,
                        'likeable_id'   => $blog->id,
                        'likeable_type' => Blog::class,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }

                // Flush every 2000 rows to prevent memory buildup
                if (count($commentRows) >= 2000) {
                    DB::table('comments')->insert($commentRows);
                    $commentRows = [];
                }

                if (count($likeRows) >= 2000) {
                    DB::table('likes')->insert($likeRows);
                    $likeRows = [];
                }
            }
        });

        // Insert any remaining rows
        if (!empty($commentRows)) {
            DB::table('comments')->insert($commentRows);
        }

        if (!empty($likeRows)) {
            DB::table('likes')->insert($likeRows);
        }

        $this->command->info('  ✓ 10,000 comments created (10 per blog)');
        $this->command->info('  ✓ 10,000 likes created (10 per blog)');
    }
}
