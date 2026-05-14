<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    public function list(Blog $blog, array $filters, ?int $userId): LengthAwarePaginator
    {
        $query = $blog->comments()
            ->with(['user'])
            ->withCount('likes')
            ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', $userId)]);

        if (!empty($filters['search'])) {
            $query->where('description', 'like', "%{$filters['search']}%");
        }

        $allowedSorts = ['description', 'likes_count', 'created_at'];
        $sortBy = in_array($filters['sort_by'] ?? '', $allowedSorts, true)
            ? $filters['sort_by']
            : 'created_at';
        $sortDir = ($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortDir);

        return $query->paginate(10);
    }

    public function create(Blog $blog, int $userId, string $description): Comment
    {
        $comment = $blog->comments()->create([
            'user_id'     => $userId,
            'description' => $description,
        ]);

        return Comment::with(['user'])
            ->withCount('likes')
            ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', $userId)])
            ->findOrFail($comment->id);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
